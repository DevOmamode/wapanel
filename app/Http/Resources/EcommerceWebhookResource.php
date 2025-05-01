<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class EcommerceWebhookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $createdAt = DateTimeHelper::convertToOrganizationTimezone($this->created_at);
        $data['created_at'] = DateTimeHelper::formatDate($createdAt);
        return $data;
    }
}
