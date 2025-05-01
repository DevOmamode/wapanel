<?php

namespace App\Http\Controllers;

use App\Events\NewChatEvent;
use App\Helpers\WebhookHelper;
use App\Http\Controllers\Controller as BaseController;
use App\Models\AutoReply;
use App\Models\Contact;
use App\Models\Chat;
use App\Models\ChatLog;
use App\Models\ChatStatusLog;
use App\Models\ChatMedia;
use App\Models\EcommerceWebhook;
use App\Models\Products;
use App\Models\Order;
use App\Models\Organization;
use App\Models\Setting;
use App\Models\Template;
use App\Resolvers\PaymentPlatformResolver;
use App\Services\AutoReplyService;
use App\Services\ChatService;
use App\Services\StripeService;
use App\Services\WhatsappService;
use App\Services\SubscriptionService;
use App\Traits\CurlTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\PhoneNumber;
use Str;

class WebhookController extends BaseController
{
    use CurlTrait;

    private $whatsappService;
    protected $paymentPlatformResolver;

    public function __construct()
    {
        $this->paymentPlatformResolver = new PaymentPlatformResolver();

        Config::set('broadcasting.connections.pusher', [
            'driver' => 'pusher',
            'key' => Setting::where('key', 'pusher_app_key')->value('value'),
            'secret' => Setting::where('key', 'pusher_app_secret')->value('value'),
            'app_id' => Setting::where('key', 'pusher_app_id')->value('value'),
            'options' => [
                'cluster' => Setting::where('key', 'pusher_app_cluster')->value('value'),
            ],
        ]);
    }

    private function initializeWhatsappService($organizationId)
    {
        $config = Organization::where('id', $organizationId)->first()->metadata;
        $config = $config ? json_decode($config, true) : [];

        $accessToken = $config['whatsapp']['access_token'] ?? null;
        $apiVersion = config('graph.api_version');
        $appId = $config['whatsapp']['app_id'] ?? null;
        $phoneNumberId = $config['whatsapp']['phone_number_id'] ?? null;
        $wabaId = $config['whatsapp']['waba_id'] ?? null;

        $this->whatsappService = new WhatsappService($accessToken, $apiVersion, $appId, $phoneNumberId, $wabaId, $organizationId);
    }

    public function whatsappWebhook(Request $request){
        //Log::info($request);
        $verifyToken = Setting::where('key', 'whatsapp_callback_token')->first()->value;

        $mode = $request->input('hub_mode');
        $token = $request->input('hub_verify_token');
        $challenge = $request->input('hub_challenge');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return Response::make($challenge, 200);
        } else {
            return Response::json(['error' => 'Forbidden'], 200);
        }
    }

    public function handle(Request $request, $identifier = null)
    {
        //Log::info('Webhook Handler: Start processing for identifier ' . $identifier);
        $organization = $this->getOrganizationByIdentifier($identifier);

        if (!$organization) {
            return $this->forbiddenResponse();
        }

        return $this->handleMethod($request, $organization);
    }

    protected function getOrganizationByIdentifier($identifier)
    {
        return Organization::where('identifier', $identifier)->first();
    }

    protected function handleMethod(Request $request, Organization $organization)
    {
        if ($request->isMethod('get')) {
            return $this->handleGetRequest($request, $organization);
        } elseif ($request->isMethod('post')) {
            $metadata = json_decode($organization->metadata);

            if (empty($metadata)) {
                return $this->forbiddenResponse();
            }

            /*$appSecret = $metadata->whatsapp->app_secret;
            $headerSignature = $request->header('X-Hub-Signature-256');
            $payload = $request->getContent();
            $calculatedSignature = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);

            if (!$this->isValidSignature($calculatedSignature, $headerSignature)) {
                return $this->invalidSignatureResponse();
            }*/

            return $this->handlePostRequest($request, $organization);
        }

        return Response::json(['error' => 'Method Not Allowed'], 405);
    }

    protected function forbiddenResponse()
    {
        return Response::json(['error' => 'Forbidden'], 403);
    }

    protected function isValidSignature($calculatedSignature, $headerSignature)
    {
        return hash_equals($calculatedSignature, $headerSignature);
    }

    protected function invalidSignatureResponse()
    {
        return Response::json(['status' => 'error', 'message' => __('Invalid payload signature')], 400);
    }

    protected function handleGetRequest(Request $request, Organization $organization)
    {
        try {
            $verifyToken = $organization->identifier;

            $mode = $request->input('hub_mode');
            $token = $request->input('hub_verify_token');
            $challenge = $request->input('hub_challenge');

            if ($mode === 'subscribe' && $token === $verifyToken) {
                return Response::make($challenge, 200);
            } else {
                return Response::json(['error' => 'Forbidden'], 404);
            }
        } catch (\Exception $e) {
            Log::error("Error processing webhook: " . $e->getMessage());
            return Response::json(['error' => $e->getMessage()], 403);
        }
    }

    protected function handlePostRequest(Request $request, Organization $organization)
    {
        $res = $request->entry[0]['changes'][0];

        // Log::info($request);

        if($res['field'] === 'messages'){
            $contacts = $res['value']['contacts'][0] ?? null;
            $messages = $res['value']['messages'] ?? null;
            $statuses = $res['value']['statuses'] ?? null;

            if($statuses) {
                //$response = $res['value']['statuses'][0];
                foreach($statuses as $response){
                    $chatWamId = $response['id'];
                    $status = $response['status'];
                    if(isset($response['type']) && $response['type'] === 'payment') {
                        $contact = Order::select('contacts.id')
                        ->leftJoin('chats', 'orders.chat_id', '=', 'chats.id')
                        ->leftJoin('contacts', 'chats.contact_id', '=', 'contacts.id')
                        ->where('orders.id', $response['payment']['reference_id'])
                        ->first();
                        $contact = Contact::where('id', $contact->id)->first();
                        if (Order::where('id', $response['payment']['reference_id'])->whereNull('payment_metadata')->exists()) {
                            Order::where('id', $response['payment']['reference_id'])->update([
                                'status' => $response['payment']['transaction']['status'] == 'success' ? 'paid' : 'unpaid',
                                'payment_metadata' => json_encode($response)
                            ]);
                            $this->initializeWhatsappService($contact->organization_id);
                            $whatsapp_pay_settings = Organization::select('whatsapp_pay_settings')->where('id', $contact->organization_id)->first()->whatsapp_pay_settings;
                            $this->whatsappService->sendMessage($contact->uuid, $whatsapp_pay_settings['payment_success_content']);
                            $this->sendDataToWebhook('order_updated', $contact->organization_id, [
                                "order_id" => $response['payment']['reference_id'],
                                "contact" => $contact,
                                "response" => $response
                            ]);
                        }
                    }

                    $chat = Chat::where('wam_id', $chatWamId)->first();

                    if($chat){
                        $chat->status = $status;
                        $chat->save();

                        $chatStatusLog = new ChatStatusLog;
                        $chatStatusLog->chat_id = $chat->id;
                        $chatStatusLog->metadata = json_encode($response);
                        $chatStatusLog->save();
                    }
                }

                // Trigger webhook
                WebhookHelper::triggerWebhookEvent('message.status.update', [
                    'data' => $res,
                ], $organization->id);
            } else if($messages) {
                $isLimitReached = SubscriptionService::isSubscriptionLimitReachedForInboundMessages($organization->id);
                //Log::info($messages);

                if(!$isLimitReached){
                    foreach($messages as $response){
                        $phone = $response['from'];

                        if (substr($phone, 0, 1) !== '+') {
                            $phone = '+' . $phone;
                        }

                        $phone = new PhoneNumber($phone);
                        $phone = $phone->formatE164();

                        //Check if contact exists in organization
                        $contact = Contact::where('organization_id', $organization->id)->where('phone', $phone)->whereNull('deleted_at')->first();
                        $isNewContact = false;

                        if(!$contact){
                            //Create a contact
                            $contactData = $res['value']['contacts'][0]['profile'] ?? null;

                            $contact = Contact::create([
                                'first_name' => $contactData['name'] ?? null,
                                'last_name' => null,
                                'email' => null,
                                'phone' => $phone,
                                'organization_id' => $organization->id,
                                'created_by' => 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                            $isNewContact = true;
                        }

                        if($contact){
                            if($contact->first_name == NULL){
                                $contactData = $res['value']['contacts'][0]['profile'] ?? null;
                                $contact->update([
                                    'first_name' => $contactData['name'],
                                ]);
                            }

                            $chat = Chat::where('wam_id', $response['id'])->where('organization_id', $organization->id)->first();

                            if(!$chat){
                                //First open the chat
                                (new ChatService($organization->id))->handleTicketAssignment($contact->id);

                                $chat = new Chat;
                                $chat->organization_id = $organization->id;
                                $chat->wam_id = $response['id'];
                                $chat->contact_id = $contact->id;
                                $chat->type = 'inbound';
                                $chat->metadata = json_encode($response);
                                $chat->status = 'delivered';
                                $chat->save();
                            
                                if($chat){
                                    $chatlogId = ChatLog::insertGetId([
                                        'contact_id' => $contact->id,
                                        'entity_type' => 'chat',
                                        'entity_id' => $chat->id,
                                        'created_at' => now()
                                    ]);
                                    if($response['type'] === 'image' || $response['type'] === 'video' || $response['type'] === 'audio' || $response['type'] === 'document' || $response['type'] === 'sticker'){
                                        $type = $response['type'];
                                        $mediaId = $response[$type]['id'];

                                        //Get & Download media file
                                        $media = $this->getMedia($mediaId, $organization);
                                        $downloadedFile = $this->downloadMedia($media, $organization);

                                        //Upload media
                                        $chatMedia = new ChatMedia;
                                        $chatMedia->name = $type === 'document' ? $response[$type]['filename'] : 'N/A';
                                        $chatMedia->path = $downloadedFile['media_url'];
                                        $chatMedia->type = $media['mime_type'];
                                        $chatMedia->size = $media['file_size'];
                                        $chatMedia->location = $downloadedFile['location'];
                                        $chatMedia->created_at = now();
                                        $chatMedia->save();

                                        //Update chat
                                        Chat::where('id', $chat->id)->update([
                                            'media_id' => $chatMedia->id
                                        ]);
                                    
                                        $this->triggerChatEvent($chatlogId, $organization->id);
                                    }
                                    else if($response['type'] === 'order') {
                                        $catalog_id = $res['value']['messages'][0]['order']['catalog_id'];
                                        $order = Order::create([
                                            "catalog_id" => $catalog_id,
                                            "organization_id" => $organization->id,
                                            "chat_id" => $chat->id,
                                            "product_items" => json_encode($res['value']['messages'][0]['order']['product_items']),
                                            "buyer" => $res['value']['contacts'][0]['profile']['name'],
                                            "phone_number" => $phone,
                                            "status" => 'ordered',
                                            "ordered_at" => date('Y-m-d H:i:s', $res['value']['messages'][0]['timestamp'])
                                        ]);
                                        $this->triggerChatEvent($chatlogId, $organization->id);
                                        $organization = Organization::select('id', 'metadata', 'product_settings', 'whatsapp_pay_settings')->whereJsonContains('metadata->whatsapp->catalog_id', $catalog_id)->first();
                                        if ($organization && isset($organization->product_settings) && $organization->product_settings) {
                                            $product_settings = $organization->product_settings;
                                            if ($product_settings['type'] == "physical_form") {
                                                $this->sendFlowMessage($res, $organization, $contact);
                                            }
                                        }
                                        $this->sendDataToWebhook('order_submitted', $organization->id, [
                                            "order_id" => $order->id,
                                            "contact" => $contact,
                                            "response" => $response,
                                            "product_items" => $res['value']['messages'][0]['order']['product_items']
                                        ]);
                                    }
                                    else if($response['type'] === 'interactive') {
                                        $response_json = $response['interactive']['nfm_reply']['response_json'];
                                        $flow = Chat::select('metadata')->where('wam_id', $response['context']['id'])->withCasts(['metadata' => 'json'])->first();
                                        $order = Order::select('orders.id')->leftJoin('chats', 'orders.chat_id', '=', 'chats.id')->where('chats.wam_id', $flow->metadata['id'])->first();
                                        Order::where('id', $order->id)->update([
                                            'shipping_chat_id' => $chat->id,
                                            'shipping_address' => $response_json
                                        ]);
                                        $this->triggerChatEvent($chatlogId, $organization->id);
                                        $order = Order::findOrFail($order->id);
                                        $organization = Organization::where('id', $order->organization_id)->first();
                                        $this->sendWhatsappPayMessage($response, $order, $organization, $contact);
                                        $this->sendDataToWebhook('order_checkout', $order->organization_id, [
                                            "order_id" => $order->id,
                                            "contact" => $contact,
                                            "response" => $response
                                        ]);
                                    }
                                    else {
                                        $this->triggerChatEvent($chatlogId, $organization->id);
                                    }
                                    
                                    $newChat = Chat::with('contact','media')->where('id', $chat->id)->first();

                                    $isMessageLimitReached = SubscriptionService::isSubscriptionFeatureLimitReached($organization->id, 'message_limit');

                                    if(!$isMessageLimitReached) {
                                        if($response['type'] === 'text' || $response['type'] === 'button'|| $response['type'] === 'audio'|| $response['type'] === 'interactive'){
                                            (new AutoReplyService)->checkAutoReply($newChat, $isNewContact);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    // Trigger webhook
                    WebhookHelper::triggerWebhookEvent('message.received', [
                        'data' => $res,
                    ], $organization->id);
                }
            }
        } else if($res['field'] === 'message_template_status_update'){
            $response = $res['value'] ?? null;
            $template = Template::where('meta_id', $response['message_template_id'])->first();

            if($template){
                $template->status = $response['event'];
                $template->save();
            }
        } else if($res['field'] === 'account_review_update'){
            //Account Status
            $response = $res['value'] ?? null;
            $organizationConfig = Organization::where('id', $organization->id)->first();
            $metadataArray = $organizationConfig->metadata ? json_decode($organizationConfig->metadata, true) : [];

            $metadataArray['whatsapp']['account_review_status'] = $response['decision'] ?? NULL;

            $updatedMetadataJson = json_encode($metadataArray);
            $organizationConfig->metadata = $updatedMetadataJson;
            $organizationConfig->save();
        } else if($res['field'] === 'phone_number_name_update'){
            //Display Name
            $response = $res['value'] ?? null;

            if($response['decision'] == 'APPROVED'){
                $organizationConfig = Organization::where('id', $organization->id)->first();
                $metadataArray = $organizationConfig->metadata ? json_decode($organizationConfig->metadata, true) : [];

                $metadataArray['whatsapp']['verified_name'] = $response['requested_verified_name'] ?? NULL;

                $updatedMetadataJson = json_encode($metadataArray);
                $organizationConfig->metadata = $updatedMetadataJson;
                $organizationConfig->save();
            }
        } else if($res['field'] === 'phone_number_quality_update'){
            //messaging_tier_limit
            $response = $res['value'] ?? null;
            $organizationConfig = Organization::where('id', $organization->id)->first();
            $metadataArray = $organizationConfig->metadata ? json_decode($organizationConfig->metadata, true) : [];

            $metadataArray['whatsapp']['messaging_limit_tier'] = $response['current_limit'] ?? NULL;

            $updatedMetadataJson = json_encode($metadataArray);
            $organizationConfig->metadata = $updatedMetadataJson;
            $organizationConfig->save();
        } else if($res['field'] === 'business_capability_update'){
            //messaging_tier_limit
            $response = $res['value'] ?? null;
            $organizationConfig = Organization::where('id', $organization->id)->first();
            $metadataArray = $organizationConfig->metadata ? json_decode($organizationConfig->metadata, true) : [];

            $metadataArray['whatsapp']['max_daily_conversation_per_phone'] = $response['max_daily_conversation_per_phone'] ?? NULL;
            $metadataArray['whatsapp']['max_phone_numbers_per_business'] = $response['max_phone_numbers_per_business'] ?? NULL;

            $updatedMetadataJson = json_encode($metadataArray);
            $organizationConfig->metadata = $updatedMetadataJson;
            $organizationConfig->save();
        }

        return Response::json(['status' => 'success'], 200);
    }
    
    private function triggerChatEvent($chatlogId, $organization_id)
    {
        $chatLogArray = ChatLog::where('id', $chatlogId)->where('deleted_at', null)->first();
        $chatArray = array([
            'type' => 'chat',
            'value' => $chatLogArray->relatedEntities
        ]);

        event(new NewChatEvent($chatArray, $organization_id));
    }

    private function downloadMedia($mediaInfo, Organization $organization)
    {
        $metadata = json_decode($organization->metadata);

        if (empty($metadata) || empty($metadata->whatsapp->access_token)) {
            return $this->forbiddenResponse();
        }

        try {
            $client = new Client();

            $requestOptions = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $metadata->whatsapp->access_token,
                    'Content-Type' => 'application/json',
                ],
            ];

            $response = $client->request('GET', $mediaInfo['url'], $requestOptions);

            $fileContent = $response->getBody();
            $mimeType = $mediaInfo['mime_type'] ?? 'application/octet-stream'; // Default fallback
            $fileName = $this->generateFilename($fileContent, $mediaInfo['mime_type']);

            $storage = Setting::where('key', 'storage_system')->first()->value;

            if($storage === 'local'){
                $location = 'local';
                $file = Storage::disk('local')->put('public/' . $fileName, $fileContent);
                $mediaFilePath = $file;
                $mediaUrl = rtrim(config('app.url'), '/') . '/media/' . 'public/' . $fileName;
            } else if($storage === 'aws') {
                $location = 'amazon';
                $filePath = 'uploads/media/received/'  . $organization->id . '/' . Str::random(40) . time();
                $file = Storage::disk('s3')->put($filePath, $fileContent, [
                    'ContentType' => $mimeType
                ]);
                $mediaUrl = Storage::disk('s3')->url($filePath);
            }

            $mediaData = [
                'media_url' => $mediaUrl,
                'location' => $location,
            ];
    
            return $mediaData;
        } catch (RequestException $e) {
            Log::error("Error processing webhook: " . $e->getMessage());
            return Response::json(['error' => 'Failed to download file'], 403);
        }
    }

    private function generateFilename($fileContent, $mimeType)
    {
        // Generate a unique filename based on the file content
        $hash = sha1($fileContent);

        // Get the file extension from the media type
        $extension = explode('/', $mimeType)[1];

        // Combine the hash, timestamp, and extension to create a unique filename
        $filename = "{$hash}_" . time() . ".{$extension}";

        return $filename;
    }

    private function getMedia($mediaId, Organization $organization)
    {
        $metadata = json_decode($organization->metadata);

        if (empty($metadata) || empty($metadata->whatsapp->access_token)) {
            return $this->forbiddenResponse();
        }

        $client = new Client();
        $responseObject = new \stdClass();

        try {
            $requestOptions = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $metadata->whatsapp->access_token,
                    'Content-Type' => 'application/json',
                ],
            ];

            $response = $client->request('GET', "https://graph.facebook.com/v18.0/{$mediaId}", $requestOptions);

            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            return Response::json(['error' => 'Method Invalid'], 400);
        }
    }

    public function processWebhook(Request $request, $processor)
    {
        $paymentPlatform = $this->paymentPlatformResolver->resolveService($processor);
        session()->put('paymentPlatform', $processor);
        
        return $paymentPlatform->handleWebhook($request);
    }

    public function sendFlowMessage($res, $organization, $contact)
    {
        $from = $res['value']['messages'][0]['from'];
        $message_id = $res['value']['messages'][0]['id'];
        $apiVersion = config('graph.api_version');
        $metadata = json_decode($organization->metadata, true);
        $product_settings = $organization->product_settings;
        $phoneNumberId = $metadata['whatsapp']['phone_number_id'];
        $bearerToken = $metadata['whatsapp']['access_token'];
        $url = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages";
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $from,
            "recipient_type" => "individual",
            "context" => [
                "message_id" => $message_id
            ],
            "type" => "interactive",
            "interactive" => [
                "type" => "flow",
                "header" => [
                    "type" => "text",
                    "text" => $product_settings['header']
                ],
                "body" => [
                    "text" => $product_settings['body']
                ],
                "footer" => [
                    "text" => $product_settings['footer']
                ],
                "action" => [
                    "name" => "flow",
                    "parameters" => [
                        "flow_message_version" => "3",
                        "flow_action" => "navigate",
                        "flow_id" => $product_settings['flow_id'],
                        "flow_cta" => $product_settings['button_text']
                    ]
                ]
            ]
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers = [
            'Content-Type: application/json',
            "Authorization: Bearer $bearerToken"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = json_decode(curl_exec($ch));
        curl_close($ch);
        if (isset($response->messages)) {
            $chat = Chat::create([
                'organization_id' => $contact->organization_id,
                'wam_id' => $response->messages[0]->id,
                'contact_id' => $contact->id,
                'type' => 'outbound',
                'metadata' => json_encode($res['value']['messages'][0]),
                'status' => 'delivered',
                'created_at' => now()
            ]);
            $chatlogId = ChatLog::insertGetId([
                'contact_id' => $contact->id,
                'entity_type' => 'chat',
                'entity_id' => $chat->id,
                'created_at' => now()
            ]);
            $this->triggerChatEvent($chatlogId, $organization->id);
        }
    }

    public function sendWhatsappPayMessage($response, $order, $organization, $contact)
    {
        $product_items = json_decode($order->product_items);
        $shipping_address = json_decode($order->shipping_address);
        if ($organization && isset($organization->api_url) && $organization->api_url) {
            $api_url = $organization->api_url;
            if ($api_url['check_status']) {
                $data = [
                    "order_id" => $order->id,
                    "response" => $response,
                    "product_items" => $product_items,
                    "shipping_address" => $shipping_address,
                ];
                $headers = [
                    'Content-Type: application/json',
                ];
                $priceData = $this->curlPost($api_url['api_url'], $data, $headers);
                Order::where('id', $order->id)->update([
                    "price_details" => json_encode($priceData)
                ]);
            }
        }
        $total_amount = $sub_total = 0;
        $groupedProductRetailerIds = collect($product_items)->pluck('product_retailer_id');
        $productDetails = Products::select('name', 'retailer_id')->whereIn('retailer_id', $groupedProductRetailerIds)->get();
        foreach ($product_items as $key => $item) {
            $items_array[] = [
                "retailer_id" => $item->product_retailer_id,
                "name" => $productDetails->firstWhere('retailer_id', $item->product_retailer_id)->name,
                "amount" => [
                    "value" => $item->item_price * 100,
                    "offset" => 100
                ],
                "sale_amount" => [
                    "value" => $item->item_price * 100,
                    "offset" => 100
                ],
                "quantity" => $item->quantity
            ];
            $total_amount += ($item->item_price * 100) * $item->quantity;
            $sub_total = $total_amount;
        }
        if (isset($priceData)) {
            $tax = $priceData['tax'] * 100;
            $shipping = $priceData['shipping'] * 100;
            $discount = $priceData['discount'] * 100;
            $total_amount += $tax + $shipping;
        } else {
            $tax = $discount = $shipping = 0;
        }
        $metadata = json_decode($organization->metadata, true);
        $whatsapp_pay_settings = $organization->whatsapp_pay_settings;
        $from = $response['from'];
        $apiVersion = config('graph.api_version');
        $phoneNumberId = $metadata['whatsapp']['phone_number_id'];
        $bearerToken = $metadata['whatsapp']['access_token'];
        $headers = [
            'Content-Type: application/json',
            "Authorization: Bearer $bearerToken"
        ];
        $url = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages";
        $data = [
            "messaging_product" => "whatsapp",
            "to" => $from,
            "recipient_type" => "individual",
            "type" => "interactive",
            "interactive" => [
                "type" => "order_details",
                "header" => [
                    "type" => "image",
                    "image" => [
                        "link" => asset('media/public/thumbnails/' . $whatsapp_pay_settings['thumbnail_image'])
                    ]
                ],
                "body" => [
                    "text" => $whatsapp_pay_settings['body_content']
                ],
                "footer" => [
                    "text" => $whatsapp_pay_settings['footer_content']
                ],
                "action" => [
                    "name" => "review_and_pay",
                    "parameters" => [
                        "reference_id" => $order->id,
                        "type" => "digital-goods",
                        "payment_type" => $whatsapp_pay_settings['payment_gateway'],
                        "payment_configuration" => $whatsapp_pay_settings['configuration_name'],
                        "currency" => "INR",
                        "total_amount" => [
                            "value" => $total_amount,
                            "offset" => 100
                        ],
                        "order" => [
                            "status" => "pending",
                            "items" => $items_array,
                            "subtotal" => [
                                "value" => $sub_total,
                                "offset" => 100
                            ],
                            "tax" => [
                                "value" => $tax,
                                "offset" => 100,
                                "description" => "description"
                            ],
                            "shipping" => [
                                "value" => $shipping,
                                "offset" => 100,
                                "description" => "description"
                            ],
                            "discount" => [
                                "value" => $discount,
                                "offset" => 100,
                                "description" => "description",
                                "discount_program_name" => "celebration"
                            ]
                        ],
                    ]
                ]
            ]
        ];
        $sendPayMessage = $this->curlPost($url, $data, $headers);
        if (isset($sendPayMessage['messages'])) {
            $chat = Chat::create([
                'organization_id' => $organization->id,
                'wam_id' => $sendPayMessage['messages'][0]['id'],
                'contact_id' => $contact->id,
                'type' => 'outbound',
                'metadata' => json_encode($data),
                'status' => 'delivered',
                'created_at' => now()
            ]);
            $chatlogId = ChatLog::insertGetId([
                'contact_id' => $contact->id,
                'entity_type' => 'chat',
                'entity_id' => $chat->id,
                'created_at' => now()
            ]);
            $this->triggerChatEvent($chatlogId, $organization->id);
        }
    }

    public function sendDataToWebhook($type, $organization_id, $data) {
        $allWebhookUrls = EcommerceWebhook::where(['organization_id' => $organization_id, 'webhook_type' => $type])->pluck('webhook_url');
        $headers = [
            'Content-Type: application/json'
        ];
        foreach ($allWebhookUrls as $key => $webhookUrl) {
            $this->curlPost($webhookUrl, $data, $headers);
        }
    }
}