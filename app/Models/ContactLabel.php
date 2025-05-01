<?php

namespace App\Models;

use App\Http\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLabel extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    public function getAll($organizationId, $searchTerm)
    {
        return $this->where('organization_id', $organizationId)
            ->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->latest()
            ->paginate(10);
    }

    public function getRow($uuid, $organizationId)
    {
        return $this->where('organization_id', $organizationId)
        ->where('uuid', $uuid)
        ->first();
    }

    public function countAll($organizationId)
    {
        return $this->where('organization_id', $organizationId)->count();
    }
}
