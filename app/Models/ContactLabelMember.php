<?php

namespace App\Models;

use App\Http\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLabelMember extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];
}
