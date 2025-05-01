<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\DateTimeHelper;
use App\Models\Chat;
use App\Models\ChatTicket;
use App\Models\ChatNote;
use Carbon\Carbon;

class ChatLog extends Model {
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    // Accessor to format created_at with organization's timezone
    public function getCreatedAtAttribute($value)
    {
        // Convert the stored UTC timestamp to the organization's timezone
        return DateTimeHelper::convertToOrganizationTimezone($value)->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return DateTimeHelper::convertToOrganizationTimezone($value)->toDateTimeString();
    }

    public function entity()
    {
        return $this->morphTo('entity');
    }

    public function getRelatedEntitiesAttribute()
    {
        $entityType = $this->entity_type;
        $entityId = $this->entity_id;
        $relatedEntity = null;

        switch ($entityType) {
            case 'chat':
                $relatedEntity = Chat::select("chats.id","chats.uuid","chats.organization_id","chats.wam_id","chats.contact_id","chats.user_id","chats.type","chats.metadata","chats.media_id","chats.status","chats.is_read","chats.deleted_by","chats.deleted_at","chats.created_at","orders.id as order_id")->with('media', 'user', 'logs')->leftJoinWhere('orders','orders.chat_id','=',$entityId)->find($entityId);
                break;
            case 'ticket':
                $relatedEntity = ChatTicketLog::find($entityId);
                break;
            case 'notes':
                $relatedEntity = ChatNote::find($entityId);
                break;
        }

        return $relatedEntity;
    }
}
