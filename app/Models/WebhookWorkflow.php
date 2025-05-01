<?php

namespace App\Models;

use App\Http\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookWorkflow extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];
    protected $casts = [
        'last_data' => 'json',
        'last_data_mapped' => 'json',
    ];

    public function getAllWebhookWorkflows($organizationId, $searchTerm)
    {
        return $this->select('webhook_workflows.id','webhook_workflows.name','webhook_workflows.template_id','webhook_workflows.uuid','webhook_workflows.status','webhook_workflows.verified','webhook_workflows.total_call','webhook_workflows.total_error','webhook_workflows.total_success','webhook_workflows.map_name','webhook_workflows.workflow_data','webhook_workflows.last_data','webhook_workflows.last_data_mapped','webhook_workflows.created_at','webhook_workflows.updated_at','templates.name as template_name','templates.metadata')
            ->where('webhook_workflows.organization_id', $organizationId)
            ->where(function ($query) use ($searchTerm) {
                $query->where('webhook_workflows.name', 'like', '%' . $searchTerm . '%');
            })->leftJoin('templates','webhook_workflows.template_id','templates.id')
            ->latest('webhook_workflows.created_at')
            ->orderBy('webhook_workflows.id')
            ->paginate(10);
    }

    public function getSingleWebhookWorkflow($organizationId,$uuid){
        return $this->select('webhook_workflows.id','webhook_workflows.name','webhook_workflows.template_id','webhook_workflows.uuid','webhook_workflows.status','webhook_workflows.verified','webhook_workflows.total_call','webhook_workflows.total_error','webhook_workflows.total_success','webhook_workflows.map_name','webhook_workflows.workflow_data','webhook_workflows.last_data','webhook_workflows.last_data_mapped','webhook_workflows.created_at','webhook_workflows.updated_at','templates.name as template_name','templates.metadata')
        ->where('webhook_workflows.organization_id', $organizationId)
        ->where('webhook_workflows.uuid',$uuid)
        ->leftJoin('templates','webhook_workflows.template_id','templates.id')
        ->first();
    }
}