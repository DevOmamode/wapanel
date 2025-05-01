<?php

namespace App\Models;

use App\Http\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];
    public $timestamps = true;

    public function getAllProducts($searchTerm,$catalog_id)
    {
        return $this->select('id','organization_id','name','uuid','price','retailer_id','product_id','currency','availability','description','image','status','assign_label','created_at','updated_at','synced_at','catalog_id')->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        })
        ->where('catalog_id',$catalog_id)
        ->latest('created_at')
        ->orderBy('id')
        ->paginate(10);
    }

    public function getOrderedProducts($product_retailer_ids,$catalog_id)
    {
        return $this->select('id','organization_id','name','uuid','price','retailer_id','product_id','currency','availability','description','image','status','assign_label','created_at','updated_at','synced_at','catalog_id')
        ->whereIn('retailer_id',$product_retailer_ids)
        ->where('catalog_id',$catalog_id)
        ->latest('created_at')
        ->orderBy('id')
        ->get();
    }
    
}