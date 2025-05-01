<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use App\Models\Template;
use App\Models\WebhookLog;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\TemplateTrait;
use App\Models\WebhookWorkflow;
use App\Services\WhatsappService;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Redirect;
use App\Http\Resources\WebhookLogResource;
use App\Http\Resources\WebhookWorkFlowResource;
use App\Http\Controllers\Controller as BaseController;

class WebhookWorkflowController extends BaseController
{
    use TemplateTrait;

    private $whatsappService;

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

    public function index(Request $request) {
        $workflowModel = new WebhookWorkflow;
        $searchTerm = $request->query('search');
        $organizationId = $request['organization'];
        $rows = WebhookWorkFlowResource::collection($workflowModel->getAllWebhookWorkflows($organizationId, $searchTerm));
        foreach($rows as $row){
            $row->resource['webhookUrl'] = route('webhook.send',['uuid'=> $row->uuid]);
            $row->resource['last_data_select'] = isset($row->last_data_mapped) && $row->last_data_mapped ? $row->last_data_mapped : '';
            $row->resource['last_data_mapped'] = isset($row->last_data_mapped) && $row->last_data_mapped ? $row->last_data_mapped : '';
        }
        $whatsappTemplates = Template::select('name as label','id as value')->where('organization_id', $organizationId)->where('deleted_at', null)->get();
        $messageTemplates = Template::where('organization_id', $organizationId)
        ->where('deleted_at', null)
        ->where('status', 'APPROVED')
        ->get();
        $config = Organization::where('id', $organizationId)->first();
        if($request->expectsJson()){
            return response()->json([
                'rows' => $rows,
                'whatsappTemplates' => $whatsappTemplates,
                'templates' => $messageTemplates,
                'settings' => $config,
                'isChatLimitReached' => SubscriptionService::isSubscriptionFeatureLimitReached($organizationId, 'message_limit')
            ]);
        } else {
            return Inertia::render('User/Webhook/Index', [
                'title' => __('WhatsApp Webhook Workflow'),
                'filters' => $request->all(),
                'rows' => $rows,
                'whatsappTemplates' => $whatsappTemplates,
                'templates' => $messageTemplates,
                'settings' => $config,
                'isChatLimitReached' => SubscriptionService::isSubscriptionFeatureLimitReached($organizationId, 'message_limit')
            ]);
        }
    }

    private function getValueByType($data, $type) {
        $array = array_filter($data, function($value) use($type) {
            return $value['type'] == $type;
        });
        return array_values($array);
    }

    private function getValueByDotNotation($data, $key) {
        $keys = explode('.', $key);
        foreach ($keys as $k) {
            if (isset($data[$k])) {
                $data = $data[$k];
            } else {
                return null;
            }
        }
        return $data;
    }

    private function buildComponent($request_data, $metadata, $workflow_data) {
        $result = [];
        foreach ($workflow_data as $key => $data) {
            if ($data['type'] == 'header' || $data['type'] == 'body') {
                $value = $this->getValueByType($workflow_data, $data['type'])[0];
                if ($data['type'] == 'header' || count($value['parameters'])) {
                    $result[] = (object) [
                        'type' => $data['type'],
                        'parameters' => $this->buildParameters($data['type'], $request_data, $metadata, $workflow_data, $value['parameters'])
                    ];
                }
                if ($metadata['category'] == 'AUTHENTICATION' && $data['type'] == 'body') {
                    $result[] = (object) [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => 0,
                        'parameters' => $this->buildParameters($data['type'], $request_data, $metadata, $workflow_data, $value['parameters'])
                    ];
                }
            }
        }
        return $result;
    }

    private function buildParameters($type, $request_data, $metadata, $workflow_data, $parameters) {
        $result = [];
        if ($type == 'header') {
            $header = $this->getValueByType($metadata['components'], 'HEADER');
            $header = isset($header[0]) ? $header[0] : null;
            if ($header) {
                if ($header['format'] == 'IMAGE') {
                    $image_data = $this->getValueByType($workflow_data, 'image')[0];
                    $result[] = (object) [
                        'type' => $header['format'],
                        'image' => (object) [
                            'link' => $this->getValueByDotNotation($request_data, $image_data['parameters'][0])
                        ]
                    ];
                }
                else if ($header['format'] == 'DOCUMENT') {
                    $document_data = $this->getValueByType($workflow_data, 'document')[0];
                    $result[] = (object) [
                        'type' => $header['format'],
                        'document' => (object) [
                            'link' => $this->getValueByDotNotation($request_data, $document_data['parameters'][0]),
                            'filename' => 'document.pdf'
                        ]
                    ];
                }
            }
        }
        foreach ($parameters as $key => $param) {
            $result[] = (object) [
                'type' => 'text',
                'text' => $this->getValueByDotNotation($request_data, $param)
            ];
        }
        return $result;
    }

    public function sendMessage(Request $request, $uuid) {
        $uuid = $request->uuid;
        $data = request()->all();
        try {
            $workflow = WebhookWorkflow::select('webhook_workflows.id', 'webhook_workflows.template_id', 'webhook_workflows.status', 'webhook_workflows.verified', 'webhook_workflows.map_name', 'webhook_workflows.workflow_data', 'templates.organization_id', 'templates.name as template_name', 'templates.language as template_language', 'templates.metadata')->where('webhook_workflows.uuid', $uuid)
            ->leftJoin('templates', 'webhook_workflows.template_id', '=', 'templates.id')->withCasts([
                'metadata' => 'json',
                'workflow_data' => 'json'
            ])->first();
            if ($workflow) {
                $template = Template::where('id', $workflow->template_id)->first();
                if ($workflow->status) {
                    WebhookWorkflow::where('uuid', $uuid)->increment('total_call', 1, ['last_data' => json_encode($data)]);
                    if (!$workflow->verified) {
                        return response()->json([
                            'statusCode' => 200,
                            'message' => 'Please verify your webhook'
                        ], 200);
                    }
                    $phone_number_data = $this->getValueByType($workflow->workflow_data, 'phone_number')[0];
                    $phone_number = $this->getValueByDotNotation($data, $phone_number_data['parameters'][0]);
                    if ($phone_number) {
                        if (substr($phone_number, 0, 1) !== '+') {
                            $phone_number = '+' . $phone_number;
                        }
                        $contact = Contact::updateOrCreate(
                            ['phone' => $phone_number, 'organization_id' => $workflow->organization_id],
                            ['created_by' => 0]
                        );
                        $contact_name = $workflow->map_name ? $this->getValueByDotNotation($data, $workflow->map_name) : null;
                        if ($contact_name) {
                            Contact::where('uuid', $contact->uuid)->update([
                                'first_name' => $contact_name
                            ]);
                        }
                    }
                    $template = [
                        'name' => $template->name,
                        'language' => [
                            'code' => $template->language
                        ],
                        'components' => $this->buildComponent($request->all(), $workflow->metadata, $workflow->workflow_data)
                    ];
                    $this->initializeWhatsappService($workflow->organization_id);
                    $responseObject = $this->whatsappService->sendTemplateMessage($contact->uuid, $template);
                    WebhookLog::create([
                        'chat_id' => $responseObject->success ? $responseObject->data->chat->id : NULL,
                        'webhook_id' => $workflow->id,
                        'phone_number' => $phone_number,
                        'metadata' => json_encode($data),
                        'template' => json_encode($template),
                        'status' => $responseObject->success ? 'success' : 'failed',
                    ]);
                    if($responseObject->success === true) {
                        WebhookWorkflow::where('uuid', $uuid)->increment('total_success', 1);
                    } else {
                        WebhookWorkflow::where('uuid', $uuid)->increment('total_error', 1);
                    }
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Webhook received successfully'
                    ], 200);
                } else {
                    return response()->json([
                        'statusCode' => 200,
                        'message' => 'Please enable your webhook'
                    ], 200);
                }
            } else {
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'No webhook found'
                ], 200);
            }
        } catch (\Exception $e) {
            WebhookLog::create([
                'chat_id' => isset($responseObject) && $responseObject->success ? $responseObject->data->chat->id : NULL,
                'webhook_id' => isset($workflow) ? $workflow->id : NULL,
                'phone_number' => isset($template) ? $phone_number : NULL,
                'metadata' => isset($template) ? json_encode($data) : NULL,
                'template' => isset($template) ? json_encode($template) : NULL,
                'status' => 'failed',
                'response' => $e->getMessage()
            ]);
            return response()->json([
                'statusCode' => 500,
                'message' => 'Webhook received successfully'
            ], 200);
        }
    }

    public function store(Request $request) {
        $templateId = $request->messageTemplate;
        $workflowName = $request->workflowName;
        WebhookWorkflow::create([
            'organization_id' => $request['organization'],
            'name' => $workflowName,
            'template_id' => $templateId,
            'status' => 1,
            'verified' => 0,
        ]);
        return Redirect::back()->with(
            'status', [
                'type' => 'success', 
                'message' => __('Workflow created successfully!')
            ]
        );
    }

    public function getRawData(Request $request){
        $lastDataMapped = WebhookWorkflow::select('last_data_mapped')->where('uuid',$request->uuid)->first()->last_data_mapped;
        return response(['lastDataMapped'=>json_encode($lastDataMapped)]);
    }
    public function recapture(Request $request){
        $WebhookWorkflow = WebhookWorkflow::select('last_data','workflow_data','last_data_mapped')->where('uuid',$request->uuid);
        $lastData = $WebhookWorkflow->first()->last_data;
        $lastDataMapped = $WebhookWorkflow->first()->last_data_mapped;
        $diffArray = false;
        if($lastDataMapped){
            $diffArray = json_encode($lastDataMapped) == json_encode($lastData);
            if($diffArray){
                $WebhookWorkflow->update(['verified' => 0, 'workflow_data'=> null]);
            }
        }
        $WebhookWorkflow->update(['last_data_mapped'=>$lastData,'workflow_data'=> null]);
        $WebhookWorkflowData = WebhookWorkflow::select('last_data','workflow_data','last_data_mapped')->where('uuid',$request->uuid)->first();
        return response(['lastDataMapped'=>$WebhookWorkflowData->last_data_mapped,'workflowData' => $WebhookWorkflowData->workflow_data,'last_data_select'=> $WebhookWorkflowData->last_data_mapped, 'diffArray' => $diffArray]);
    }
    public function statusUpdate(Request $request){
        $status = ($request->status == "true") ? 1 : 0;
        WebhookWorkflow::select('status')->where('uuid',$request->uuid)->update(['status'=>$status]);
        return response(['message'=>'succsess']);
    }

    public function delete($uuid){
        $WebhookWorkflow = WebhookWorkflow::where('uuid',$uuid)->delete();
        if($WebhookWorkflow == true){
            return response()->json([
                'success' => true,
                'message'=> __('Webhook deleted successfully')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message'=> __('something went wrong. Refresh the page and try again')
            ]);
        }
    }

    public function workflowStore(Request $request){
        WebhookWorkflow::select('status')->where('uuid',$request->uuid)->update(['verified' => 1, 'workflow_data'=>$request->workflow_json,'map_name' => $request->subscriber_name]);
        $workflowModel = new WebhookWorkflow;
        $organizationId = $request['organization'];
        $workflowDetail = $workflowModel->getSingleWebhookWorkflow($organizationId,$request->uuid);
        $workflowDetail->webhookUrl = route('webhook.send',['uuid'=> $workflowDetail->uuid]);
        $workflowDetail->last_data_select = isset($workflowDetail->last_data_mapped) && $workflowDetail->last_data_mapped ? $workflowDetail->last_data_mapped : '';
        $workflowDetail->last_data_mapped = isset($workflowDetail->last_data_mapped) && $workflowDetail->last_data_mapped ? $workflowDetail->last_data_mapped : '';
        return response(['message'=>'succsess','workflowDetail'=>$workflowDetail]);
    }

    public function whatsappWorkflowReport(Request $request,$uuid) {
        $WebhookLog = new WebhookLog;
        $WebhookLog = WebhookLogResource::collection($WebhookLog->getWebhookReport($uuid));
        if($request->expectsJson()){
            return response()->json([
                'rows' => $WebhookLog,
            ]);
        } else {
            return Inertia::render('User/Webhook/WorkflowReport', [
                'title' => __('WhatsApp Webhook Workflow Report'),
                'filters' => $request->all(),
                'rows' => $WebhookLog,
            ]);
        }
    }

    public function whatsappDataFormatterStore(Request $request) {
        dd($request->all());
    }

    public function whatsappDataFormatterDelete(Request $request,$uuid) {
        dd($request->all(),$uuid);
    }

}