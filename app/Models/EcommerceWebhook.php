<?php

namespace App\Models;

use App\Http\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EcommerceWebhook extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function getEcommerceWebhooks($catalog_id,$organizationId,$searchTerm){
        return $this->where('ecommerce_webhooks.organization_id', $organizationId)->where('ecommerce_webhooks.catalog_id',$catalog_id)->where(function ($query) use ($searchTerm) {
            $query->where('ecommerce_webhooks.webhook_url', 'like', '%' . $searchTerm . '%');
        })
        ->latest('ecommerce_webhooks.created_at')
        ->orderBy('ecommerce_webhooks.id')
        ->paginate(10);
    }
}
