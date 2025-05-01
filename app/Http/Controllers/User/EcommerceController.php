<?php

namespace App\Http\Controllers\User;

use Date;
use Carbon\Carbon;
use App\Models\Chat;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\Contact;
use App\Models\Products;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use App\Models\EcommerceWebhook;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\OrderResource;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProductsResource;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\EcommerceWebhookResource;

class EcommerceController extends Controller
{
    private $organizationService;

    // /**
    //  * OrganizationController constructor.
    //  *
    //  * @param UserService $organizationService
    //  */
    public function __construct()
    {
        $this->organizationService = new OrganizationService();
    }
    public function products(Request $request) {
        $orgMetaData =  $this->organizationService->getMetaData($request);
        $catalog_id =  isset($orgMetaData->whatsapp) && isset($orgMetaData->whatsapp->catalog_id) ? $orgMetaData->whatsapp->catalog_id : null;
        $productModel = new Products;
        $rows = ProductsResource::collection($productModel->getAllProducts($request->query('search'),$catalog_id));
        if($request->expectsJson()){
            return response()->json([
                'rows' => $rows,
                'catalog_id' => $catalog_id,
            ]);
        } else {
            return Inertia::render('User/Ecommerce/Index', [
                'title' => __('Ecommerce'),
                'filters' => $request->all(),
                'rows' => $rows,
                'catalog_id' => $catalog_id ?? null,
            ]);
        }
    }

    public function orders(Request $request) {
        $organizationId = $request['organization'];
        $orgMetaData =  $this->organizationService->getMetaData($request);
        $catalog_id =  isset($orgMetaData->whatsapp) && isset($orgMetaData->whatsapp->catalog_id) ? $orgMetaData->whatsapp->catalog_id : null;
        $productsCount= Products::select('id')->where('catalog_id',$catalog_id)->count();
        $orderModel = new Order;   
        $orders = OrderResource::collection($orderModel->getOrders($catalog_id,$organizationId));
        foreach ($orders->resource as $key => $value) {
            $total = 0;
            $currency = null;
            $product_retailer_ids = [];
            $product_retailer_id_with_quantity = [];
            foreach (json_decode($value->product_items) as $val) {
                $total += $val->item_price * $val->quantity;
                $currency = $val->currency;
                array_push($product_retailer_ids, $val->product_retailer_id);
                array_push($product_retailer_id_with_quantity, array('product_retailer_id' => $val->product_retailer_id,'quantity' => $val->quantity,'productsPrice' => $val->item_price * $val->quantity));
            }
            $value->resource->total = $total;
            $value->resource->currency = $currency;
            $value->resource->product_retailer_ids = $product_retailer_ids;
            $value->resource->product_retailer_id_with_quantity = $product_retailer_id_with_quantity;
        }
        if($request->expectsJson()){
            return response()->json([
                'rows'=>$orders,
                'productsCount' => $productsCount,
                'catalog_id' => $catalog_id,
            ]);
        } else {
            return Inertia::render('User/Ecommerce/Orders', [
                'title' => __('Ecommerce'),
                'filters' => $request->all(),
                'rows'=>$orders,
                'productsCount' => $productsCount,
                'catalog_id' => $catalog_id ?? null,
            ]);
        }
    }

    public function settings(Request $request) {
        $orgMetaData =  $this->organizationService->getMetaData($request);
        $orgProductAndWhatsappPaySettings =  $this->organizationService->getProductAndWhatsappPaySettings($request);
        $orgProductSettings = $orgProductAndWhatsappPaySettings['orgProductSettings'];
        $orgWhatsappPaySettings = $orgProductAndWhatsappPaySettings['orgWhatsappPaySettings'];
        if($orgWhatsappPaySettings && $orgWhatsappPaySettings->thumbnail_image){
            $orgWhatsappPaySettings->thumbnail_image = '/media/public/thumbnails/'.$orgWhatsappPaySettings->thumbnail_image;
        }
        $orgPaymentDetailsApi = $orgProductAndWhatsappPaySettings['orgPaymentDetailsApi'];  
        $catalog_id =  isset($orgMetaData->whatsapp) && isset($orgMetaData->whatsapp->catalog_id) ? $orgMetaData->whatsapp->catalog_id : null;
        $productsCount= Products::select('id')->where('catalog_id',$catalog_id)->count();
        if($request->expectsJson()){
            return response()->json([
                'productsCount' => $productsCount,
                'catalog_id' => $catalog_id,
                'orgProductSettings' => $orgProductSettings ?? null,
                'orgWhatsappPaySettings' => $orgWhatsappPaySettings ?? null,
                'orgPaymentDetailsApi' => $orgPaymentDetailsApi ?? null,
            ]);
        } else {
            return Inertia::render('User/Ecommerce/Settings', [
                'title' => __('Ecommerce'),
                'filters' => $request->all(),
                'productsCount' => $productsCount,
                'catalog_id' => $catalog_id ?? null,
                'orgProductSettings' => $orgProductSettings ?? null,
                'orgWhatsappPaySettings' => $orgWhatsappPaySettings ?? null,
                'orgPaymentDetailsApi' => $orgPaymentDetailsApi ?? null,
            ]);
        }
    }

    public function webhook(Request $request) {
        $organizationId = $request['organization'];
        $orgMetaData =  $this->organizationService->getMetaData($request);
        $catalog_id =  isset($orgMetaData->whatsapp) && isset($orgMetaData->whatsapp->catalog_id) ? $orgMetaData->whatsapp->catalog_id : null;
        $productsCount= Products::select('id')->where('catalog_id',$catalog_id)->count();
        $ecommerceWebhookModel = new EcommerceWebhook;    
        $ecommerceWebhook = EcommerceWebhookResource::collection($ecommerceWebhookModel->getEcommerceWebhooks($catalog_id,$organizationId,$request->query('search')));
        if($request->expectsJson()){
            return response()->json([
                'rows' => $ecommerceWebhook,
                'productsCount' => $productsCount,
                'catalog_id' => $catalog_id,
            ]);
        } else {
            return Inertia::render('User/Ecommerce/Webhook', [
                'title' => __('Ecommerce'),
                'filters' => $request->all(),
                'rows' => $ecommerceWebhook,
                'productsCount' => $productsCount,
                'catalog_id' => $catalog_id ?? null,
            ]);
        }
    }
    public function storeCatalog(Request $request){
        $organizationId = $request['organization'];
        $orgMetaData =  $this->organizationService->getMetaData($request);
        $orgMetaData->whatsapp->catalog_id = $request->catalog_id;
        Organization::where('id',$organizationId)->update(['metadata'=>json_encode($orgMetaData)]);
        return Redirect::route('ecommerce.index')->with(
            'status', [
                'type' => 'success', 
                'message' => __('Catalog Added successfully!')
            ]
        );
    }

    public function syncProducts(Request $request){
        try{
            $orgMetaData =  $this->organizationService->getMetaData($request);
            $orgProductAndWhatsappPaySettings =  $this->organizationService->getProductAndWhatsappPaySettings($request);
            $orgProductSettings = $orgProductAndWhatsappPaySettings['orgProductSettings'];
            $orgWhatsappPaySettings = $orgProductAndWhatsappPaySettings['orgWhatsappPaySettings'];
            $orgPaymentDetailsApi = $orgProductAndWhatsappPaySettings['orgPaymentDetailsApi'];
            $accessToken = $orgMetaData->whatsapp->access_token;
            $fields = 'id,name,retailer_id,price,currency,availability,description,image_url';
            $catalog_id =  $orgMetaData->whatsapp->catalog_id;
            $response = Http::get("https://graph.facebook.com/v20.0/{$catalog_id}/products", [
                'fields' => $fields,
                'access_token' => $accessToken,
                'limit' => 1000
            ])->throw()->json();
            if (isset($response['data']['error'])) {
                $status = 'Failed';
                $rows = null;
            }else{
                foreach ((object) $response['data'] as $key => $value) {
                    Products::updateOrCreate(
                        ['product_id' => $value['id']],
                        ['organization_id' => $request['organization'],'name'=>$value['name'],'image' => $value['image_url'],'retailer_id' => $value['retailer_id'], 'price' =>$value['price'],'currency' =>  $value['currency'] , 'availability' => $value['availability'] , 'description' => $value['description'],'synced_at' => Carbon::now(),'catalog_id' => $catalog_id]
                    );
                }
                $productModel = new Products;
                $rows = ProductsResource::collection($productModel->getAllProducts($request->query('search'),$catalog_id));
                $orderModel = new Order;        
                $orders = OrderResource::collection($orderModel->getOrders($catalog_id,$request['organization']));
                foreach ($orders->resource as $key => $value) {
                    $total = 0;
                    $currency = null;
                    $product_retailer_ids = [];
                    $product_retailer_id_with_quantity = [];
                    foreach (json_decode($value->product_items) as $val) {
                        $total += $val->item_price * $val->quantity;
                        $currency = $val->currency;
                        array_push($product_retailer_ids, $val->product_retailer_id);
                        array_push($product_retailer_id_with_quantity, array('product_retailer_id' => $val->product_retailer_id,'quantity' => $val->quantity,'productsPrice' => $val->item_price * $val->quantity));
                    }
                    $value->resource->total = $total;
                    $value->resource->currency = $currency;
                    // $productModel1 = new Products;
                    // $value->resource->products = ProductsResource::collection($productModel1->getOrderedProducts($product_retailer_ids,$catalog_id))->response()->getData(true);
                    $value->resource->product_retailer_ids = $product_retailer_ids;
                    $value->resource->product_retailer_id_with_quantity = $product_retailer_id_with_quantity;
                }
                $status = 'Success';
            }

            return Redirect::back()->with(
                'status', [
                    'type' => 'success', 
                    'message' => __('Synced Successfully!')
                ]
            );

        } catch (\Exception $e) {
            return response()->json([
                'statusCode' => 500,
                'message' => __('Request unable to be processed')
            ], 500);
        }

    }

    public function removeCatalog(Request $request){
        $organizationId = $request['organization'];
        $orgMetaData =  $this->organizationService->getMetaData($request);
        Products::where('catalog_id',$orgMetaData->whatsapp->catalog_id)->delete();
        unset($orgMetaData->whatsapp->catalog_id);
        Organization::where('id',$organizationId)->update(['metadata'=>json_encode($orgMetaData)]);
        return response()->json(['status' => 'success']);
    }

    public function storeEcommerceSettings(Request $request) {
        if ($request->settingFormType == "physical_form") {
            $product_settings = (object) array(
                "type" => $request->settingFormType,
                "header" => $request->header,
                "body" => $request->body,
                "footer" => $request->footer,
                "button_text" => $request->button_text,
                "flow_id" => $request->flow_id
            );
            Organization::where('id', $request->organization)->update([
                'product_settings' => json_encode($product_settings)
            ]);
            return Redirect::back()->with(
                'status', [
                    'type' => 'success', 
                    'message' => __('Details Saved Successfully!')
                ]
            );
        }
    }

    public function storeWhatsAppPayment(Request $request) {
        if ($request->settingFormType == "digital_form") {
            $product_settings = (object) [
                "type" => $request->settingFormType
            ];
            Organization::where('id', $request->organization)->update([
                'product_settings' => json_encode((object) $product_settings)
            ]);
        }
        if($request->hasFile('file')){
            $file = $request->file('file');
            $fileName = time() . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('thumbnails', $file, $fileName);
        }else{
            $fileName = basename($request->file);
        }
        $whatsapp_pay_settings = (object) array(
            "thumbnail_image" => $fileName,
            "body_content" => $request->body_content,
            "footer_content" => $request->footer_content,
            "payment_gateway" => $request->payment_gateway,
            "configuration_name" => $request->configuration_name,
            "payment_success_content" => $request->payment_success_content,
        );
        Organization::where('id', $request->organization)->update([
            'whatsapp_pay_settings' => json_encode($whatsapp_pay_settings)
        ]);
        return Redirect::back()->with(
            'status', [
                'type' => 'success', 
                'message' => __('WhatsApp Payment Settings Saved Successfully!')
            ]
        );
    }

    public function getOrderProducts(Request $request){
        $productModel = new Products;
        $products = ProductsResource::collection($productModel->getOrderedProducts(json_decode($request->product_retailer_ids),$request->catalog_id))->response()->getData(true);
        return response(['products' => $products]);
    }

    public function storeApiURL(Request $request) {
        $api_url = (object) array(
            "check_status" => $request->checkEnable,
            "api_url" => $request->apiUrl,
        );
        Organization::where('id', $request->organization)->update(['api_url' => json_encode($api_url)]);
        return Redirect::back()->with(
            'status', [
                'type' => 'success', 
                'message' => __('API Saved Successfully!')
            ]
        );
    }

    public function storeEcommerceWebhook (Request $request) {
        $orgMetaData =  $this->organizationService->getMetaData($request);
        $catalog_id =  isset($orgMetaData->whatsapp) && isset($orgMetaData->whatsapp->catalog_id) ? $orgMetaData->whatsapp->catalog_id : null;
        $EcommerceWebhook = EcommerceWebhook::create(['organization_id'=> $request->organization, 'catalog_id' => $catalog_id,'webhook_url' => $request->webhookUrl,'webhook_type' => $request->eventType]);
        return Redirect::back()->with(
            'status', [
                'type' => 'success', 
                'message' => __('Webhook Added Successfully!')
            ]
        );
    }

    public function deleteEcommerceWebhook ($uuid) {
        $WebhookWorkflow = EcommerceWebhook::where('uuid',$uuid)->delete();
        if($WebhookWorkflow == true){
            return Redirect::back()->with(
                'status', [
                    'type' => 'success', 
                    'message' => __('Webhook deleted successfully!')
                ]
            );
        } else {
            return Redirect::back()->with(
                'status', [
                'success' => false,
                'message'=> __('something went wrong. Refresh the page and try again')
            ]);
        }
    }
}