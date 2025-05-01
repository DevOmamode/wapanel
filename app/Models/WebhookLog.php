<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function getWebhookReport($uuid){
        return $this->select('webhook_logs.id','webhook_logs.chat_id','webhook_logs.phone_number','webhook_logs.metadata','webhook_logs.template', 'webhook_logs.response', 'webhook_logs.status','webhook_logs.created_at','webhook_logs.updated_at','webhook_workflows.name')
        ->leftJoin('webhook_workflows','webhook_workflows.id','webhook_logs.webhook_id')
        ->where('webhook_workflows.uuid',$uuid)
        ->latest('webhook_logs.created_at')
        ->paginate(10);
    }

}
