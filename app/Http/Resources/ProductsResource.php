<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        
         // Convert updated_at to the organization's timezone and format it
         $syncedAt = DateTimeHelper::convertToOrganizationTimezone($this->synced_at);
         $data['synced_at'] = DateTimeHelper::formatDate($syncedAt);
 
         return $data;
    }
}
