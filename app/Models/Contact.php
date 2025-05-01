<?php

namespace App\Models;

use App\Http\Traits\HasUuid;
use App\Models\ContactLabel;
use App\Helpers\DateTimeHelper;
use App\Models\ContactLabelMember;
use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model {
    use HasFactory;
    use HasUuid;
    use SoftDeletes;

    protected $guarded = [];
    protected $appends = ['full_name', 'formatted_phone_number'];
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    public function getCreatedAtAttribute($value)
    {
        return DateTimeHelper::convertToOrganizationTimezone($value)->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return DateTimeHelper::convertToOrganizationTimezone($value)->toDateTimeString();
    }

    public function getAllContacts($organizationId, $searchTerm, $splitedLabelState = ['all'])
    {
        return $this->select("contacts.id","contacts.uuid","contacts.organization_id","contacts.first_name","contacts.last_name","contacts.phone","contacts.email","contacts.latest_chat_created_at","contacts.avatar","contacts.address","contacts.metadata","contacts.contact_group_id","contacts.is_favorite","contacts.ai_assistance_enabled","contacts.created_by","contacts.created_at","contacts.updated_at","contacts.deleted_at")->with(['contactGroup'])
            ->where('contacts.organization_id', $organizationId)
            ->where('contacts.deleted_at', null)
            ->when(count($splitedLabelState) > 0 && $splitedLabelState[0] != 'all',function ($query) use ($splitedLabelState) {
                $query->leftJoin('contact_label_members as clm', 'clm.contact_id', 'contacts.id');
                $query->leftJoin('contact_labels as cl', 'cl.id', 'clm.contact_label_id');
                $query->whereIn('cl.name', $splitedLabelState);
            })
            ->where(function ($query) use ($searchTerm) {
                $query->where('contacts.first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('contacts.last_name', 'like', '%' . $searchTerm . '%')
                
                // Split the search term into parts and check for matches in both columns
                ->orWhere(function ($subQuery) use ($searchTerm) {
                    $searchParts = explode(' ', $searchTerm);
                    if (count($searchParts) > 1) {
                        $subQuery->where('contacts.first_name', 'like', '%' . $searchParts[0] . '%')
                                ->where('contacts.last_name', 'like', '%' . $searchParts[1] . '%');
                    }
                })
                
                // Match phone or email
                ->orWhere('contacts.phone', 'like', '%' . $searchTerm . '%')
                ->orWhere('contacts.email', 'like', '%' . $searchTerm . '%');
            })
            ->orderByDesc('contacts.is_favorite')
            ->latest()
            ->orderBy('contacts.id')
            ->paginate(10);
    }

    public function getAllContactGroups($organizationId)
    {
        return ContactGroup::where('organization_id', $organizationId)->whereNull('deleted_at')->get();
    }

    public function countContacts($organizationId,$searchTerm,$splitedLabelState = ['all'])
    {
        return $this->where('contacts.organization_id', $organizationId)
        ->when(count($splitedLabelState) > 0 && $splitedLabelState[0] != 'all',function ($query) use ($splitedLabelState) {
            $query->leftJoin('contact_label_members as clm', 'clm.contact_id', 'contacts.id');
            $query->leftJoin('contact_labels as cl', 'cl.id', 'clm.contact_label_id');
            $query->whereIn('cl.name', $splitedLabelState);
        })->where(function ($query) use ($searchTerm) {
            $query->where('contacts.first_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('contacts.last_name', 'like', '%' . $searchTerm . '%')
            
            // Split the search term into parts and check for matches in both columns
            ->orWhere(function ($subQuery) use ($searchTerm) {
                $searchParts = explode(' ', $searchTerm);
                if (count($searchParts) > 1) {
                    $subQuery->where('contacts.first_name', 'like', '%' . $searchParts[0] . '%')
                            ->where('contacts.last_name', 'like', '%' . $searchParts[1] . '%');
                }
            })
            
            // Match phone or email
            ->orWhere('contacts.phone', 'like', '%' . $searchTerm . '%')
            ->orWhere('contacts.email', 'like', '%' . $searchTerm . '%');
        })->whereNull('contacts.deleted_at')->count();
    }

    public function contactGroup()
    {
        return $this->belongsTo(ContactGroup::class, 'contact_group_id', 'id')->withTrashed();
    }

    public function notes()
    {
        return $this->hasMany(ChatNote::class, 'contact_id')->orderBy('created_at', 'desc');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'contact_id')->orderBy('created_at', 'asc');
    }

    public function lastChat()
    {
        return $this->hasOne(Chat::class, 'contact_id')->with('media')->latest();
    }

    public function lastInboundChat()
    {
        return $this->hasOne(Chat::class, 'contact_id')
                    ->where('type', 'inbound')
                    ->with('media')
                    ->latest();
    }

    public function chatLogs()
    {
        return $this->hasMany(ChatLog::class);
    }

    public function contactsWithChats($organizationId, $searchTerm = null, $ticketingActive = false, $ticketState = null, $sortDirection = 'asc', $role = 'owner', $allowAgentsViewAllChats = true)
    {
        $query = $this->newQuery()
            ->where('contacts.organization_id', $organizationId)
            ->with(['lastChat', 'lastInboundChat'])
            ->whereNull('contacts.deleted_at')
            ->select('contacts.*')
            ->selectSub(function ($subquery) use ($organizationId) {
                $subquery->from('chats')
                    ->selectRaw('MAX(created_at)')
                    ->whereColumn('chats.contact_id', 'contacts.id')
                    ->whereNull('chats.deleted_at')
                    ->where('chats.organization_id', $organizationId);
            }, 'last_chat_created_at');

        // Apply ticketing conditions if active
        if ($ticketingActive) {
            $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id');

            if ($ticketState === 'unassigned') {
                $query->whereNull('chat_tickets.assigned_to');
            } elseif ($ticketState === 'mine') {
                $query->where('chat_tickets.assigned_to', auth()->user()->id);
            } elseif ($ticketState !== null && $ticketState !== 'all') {
                $query->where('chat_tickets.status', $ticketState);
            }

            if ($role === 'agent' && !$allowAgentsViewAllChats) {
                $query->where('chat_tickets.assigned_to', auth()->user()->id);
            }
        }

        // Search filter
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('contacts.first_name', 'like', "%$searchTerm%")
                    ->orWhere('contacts.last_name', 'like', "%$searchTerm%")
                    ->orWhereRaw("CONCAT(contacts.first_name, ' ', contacts.last_name) LIKE ?", ["%$searchTerm%"])
                    ->orWhere('contacts.phone', 'like', "%$searchTerm%")
                    ->orWhere('contacts.email', 'like', "%$searchTerm%");
            });
        }

        // Order by the latest chat's created_at
        $query->orderBy('last_chat_created_at', $sortDirection); // Order contacts by last chat created_at

        // Paginate contacts
        $contacts = $query->paginate(10);

        return $contacts;

        /*$query = $this->newQuery()
            ->where('contacts.organization_id', $organizationId)
            ->whereNotNull('contacts.latest_chat_created_at')
            ->whereNull('contacts.deleted_at')
            ->with(['lastChat', 'lastInboundChat'])
            ->select('contacts.*')
            ->orderBy('contacts.latest_chat_created_at', $sortDirection);

        if($ticketingActive){
            // Conditional join with chat_tickets table and comparison with ticketState
            if ($ticketState === 'unassigned') {
                $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id')
                    ->whereNull('chat_tickets.assigned_to');
            } elseif ($ticketState !== null && $ticketState !== 'all') {
                $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id')
                    ->where('chat_tickets.status', $ticketState);
            } else if($ticketState === 'all'){
                $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id');
            }

            if($role == 'agent' && $allowAgentsViewAllChats == false){
                $query->where(function($q) {
                    $q->where('chat_tickets.assigned_to', auth()->user()->id);
                });
            }
        }

        // Include the search term in the query if provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('contacts.first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('contacts.last_name', 'like', '%' . $searchTerm . '%')
                
                // Split the search term into parts and check for matches in both columns
                ->orWhere(function ($subQuery) use ($searchTerm) {
                    $searchParts = explode(' ', $searchTerm);
                    if (count($searchParts) > 1) {
                        $subQuery->where('contacts.first_name', 'like', '%' . $searchParts[0] . '%')
                                ->where('contacts.last_name', 'like', '%' . $searchParts[1] . '%');
                    }
                })
                
                // Match phone or email
                ->orWhere('contacts.phone', 'like', '%' . $searchTerm . '%')
                ->orWhere('contacts.email', 'like', '%' . $searchTerm . '%');
            });
        }

        return $query->paginate(10);*/
    }

    public function contactsWithChatsCount($organizationId, $searchTerm = null, $ticketingActive = false, $ticketState = null, $sortDirection = 'asc', $role = 'owner', $allowAgentsViewAllChats = true)
    {
        $query = $this->newQuery()
            ->where('contacts.organization_id', $organizationId)
            ->whereNotNull('contacts.latest_chat_created_at')
            ->whereNull('contacts.deleted_at')
            ->with(['lastChat', 'lastInboundChat'])
            ->select('contacts.*')
            ->orderBy('contacts.latest_chat_created_at', $sortDirection);

        if($ticketingActive){
            // Conditional join with chat_tickets table and comparison with ticketState
            if ($ticketState === 'unassigned') {
                $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id')
                    ->whereNull('chat_tickets.assigned_to');
            } elseif ($ticketState === 'mine') {
                $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id')
                ->where('chat_tickets.assigned_to', auth()->user()->id);
            } elseif ($ticketState !== null && $ticketState !== 'all') {
                $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id')
                    ->where('chat_tickets.status', $ticketState);
            } else if($ticketState === 'all'){
                $query->leftJoin('chat_tickets', 'contacts.id', '=', 'chat_tickets.contact_id');
            }

            if($role == 'agent' && $allowAgentsViewAllChats == false){
                $query->where(function($q) {
                    $q->where('chat_tickets.assigned_to', auth()->user()->id);
                });
            }
        }

        if($role == 'agent' && $allowAgentsViewAllChats == false){
            $query->where(function($q) {
                $q->whereNull('chat_tickets.assigned_to')
                  ->orWhere('chat_tickets.assigned_to', auth()->user()->id);
            });
        }

        // Include the search term in the query if provided
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('contacts.first_name', 'like', '%' . $searchTerm . '%')
                ->orWhere('contacts.last_name', 'like', '%' . $searchTerm . '%')
                
                // Split the search term into parts and check for matches in both columns
                ->orWhere(function ($subQuery) use ($searchTerm) {
                    $searchParts = explode(' ', $searchTerm);
                    if (count($searchParts) > 1) {
                        $subQuery->where('contacts.first_name', 'like', '%' . $searchParts[0] . '%')
                                ->where('contacts.last_name', 'like', '%' . $searchParts[1] . '%');
                    }
                })
                
                // Match phone or email
                ->orWhere('contacts.phone', 'like', '%' . $searchTerm . '%')
                ->orWhere('contacts.email', 'like', '%' . $searchTerm . '%');
            });
        }

        return $query->count();
    }

    public function getFirstNameAttribute()
    {
        $firstName = $this->attributes['first_name'];
        $firstName = $this->decodeUnicodeBytes($firstName);

        return $firstName;
    }

    public function getLastNameAttribute()
    {
        $lastName = $this->attributes['last_name'];
        $lastName = $this->decodeUnicodeBytes($lastName);

        return $lastName;
    }

    public function getFullNameAttribute()
    {
        $firstName = $this->attributes['first_name'];
        $lastName = $this->attributes['last_name'];

        // Convert byte sequences to Unicode characters
        $firstName = $this->decodeUnicodeBytes($firstName);
        $lastName = $this->decodeUnicodeBytes($lastName);

        // Return the full name combining first name and last name
        return $firstName . ' ' . $lastName;

        //return "{$this->first_name} {$this->last_name}";
    }

    public function getFormattedPhoneNumberAttribute($value)
    {
        // Use the phone() helper function to format the phone number to international format
        return phone($this->phone)->formatInternational();
    }

    protected function decodeUnicodeBytes($value)
    {
        return preg_replace_callback('/\\\\x([0-9A-F]{2})/i', function ($matches) {
            return chr(hexdec($matches[1]));
        }, $value);
    }
    
    public function getAllContactLabels($organizationId)
    {
        return ContactLabel::where('organization_id', $organizationId)->get();
    }

    public function contactLabelMemebers()
    {
        return $this->hasMany(ContactLabelMember::class, 'contact_id')->leftJoin('contact_labels as cl', 'cl.id', 'contact_label_members.contact_label_id');
    }
}
