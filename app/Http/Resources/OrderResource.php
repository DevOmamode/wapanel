<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $data = parent::toArray($request);
        
        // Convert updated_at to the organization's timezone and format it
        $createdAt = DateTimeHelper::convertToOrganizationTimezone($this->created_at);
        $data['created_at'] = DateTimeHelper::formatDate($createdAt);
        $updatedAt = DateTimeHelper::convertToOrganizationTimezone($this->updated_at);
        $data['updated_at'] = DateTimeHelper::formatDate($updatedAt);
        $orderedAt = DateTimeHelper::convertToOrganizationTimezone($this->ordered_at);
        $data['ordered_at'] = DateTimeHelper::formatDate($orderedAt);
        return $data;
    }
}
