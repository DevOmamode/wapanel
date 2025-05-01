<?php

namespace App\Models;

use App\Http\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use HasUuid;
    
    protected $guarded = [];

     public function getOrders($catalog_id,$organizationId){
        return $this->where('orders.organization_id',$organizationId)->where('orders.catalog_id',$catalog_id)->latest('orders.ordered_at')
        ->orderBy('orders.id')
        ->paginate(10);
    }
}
