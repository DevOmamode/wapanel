<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppWidget extends Model
{
    use HasFactory;
    public $table = 'whatsapp_widgets';
    protected $fillable = [
        'business_name', 
        'whatsapp_number', 
        'status_message',
        'profile_image',
        'widget_position', 
        'welcome_message', 
        'secondary_message', 
        'button1', 
        'button2', 
        'button3', 
        'user_id', 
        'status'
    ];
}
