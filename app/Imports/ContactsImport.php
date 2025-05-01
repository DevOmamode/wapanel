<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\ContactField;
use App\Models\ContactGroup;
use App\Models\ContactLabelMember;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Propaganistas\LaravelPhone\PhoneNumber;

class ContactsImport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements ToModel, WithHeadingRow, WithCustomValueBinder, WithChunkReading
{
    protected $successfulImports = 0;
    protected $totalImports = 0;
    protected $failedImportsDueToFirstName = 0;
    protected $failedImportsDueToFormat = 0;
    protected $failedImportsDueToDuplicates = 0;
    public $selectedContactLabelIds = [];

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            $this->totalImports++;
            $phoneNumberValue = $row['phone'];

            if (!str_starts_with($phoneNumberValue, '+')) {
                $phoneNumberValue = '+' . $phoneNumberValue;
            }

            $validator = Validator::make($row, [
                'first_name' => [
                    function ($attribute, $value, $fail) use ($row) {
                        if (empty($value)) {
                            $this->failedImportsDueToFirstName++;
                            $fail('The '.$attribute.' is required.');
                        }
                    },
                    'required',
                ],
                'phone' => [
                    'required',
                    function ($attribute, $value, $fail) use ($phoneNumberValue) {
                        $phoneNumber = new PhoneNumber($phoneNumberValue);

                        if (!$phoneNumber->isValid()) {
                            $this->failedImportsDueToFormat++;
                            $fail('The '.$attribute.' is invalid.');
                        }

                        // Check if the phone number already exists in the database
                        if (Contact::where('organization_id', session()->get('current_organization'))->where('phone', $phoneNumber)->whereNull('deleted_at')->exists()) {
                            $this->failedImportsDueToDuplicates++;
                            $fail('The '.$attribute.' already exists.');
                        }
                    },
                ]
            ]);

            if ($validator->fails()) {
                \Log::error($validator->errors()->all());
                return null;
            }

            $group = null;

            if(isset($row['group_name'])){
                $group = ContactGroup::where('organization_id', session()->get('current_organization'))
                    ->where('name', $row['group_name'])
                    ->whereNull('deleted_at')
                    ->first();

                if(!$group){
                    $group = ContactGroup::create([
                        'organization_id'  => session()->get('current_organization'),
                        'name' => $row['group_name'],
                        'created_by'  => auth()->user()->id,
                    ]);
                }
            }

            // Fetch dynamic fields from contact_fields table
            $organizationId = session()->get('current_organization');
            $contactFields = ContactField::where('organization_id', $organizationId)->pluck('name')->toArray();

            $metadata = [];

            foreach ($contactFields as $field) {
                $normalizedField = strtolower($field); 

                if (isset($row[$normalizedField])) {
                    $metadata[$field] = $row[$normalizedField];
                }
            }
            
            $contact = Contact::create([
                'organization_id'  => $organizationId,
                'first_name'  => $row['first_name'],
                'last_name'   => $row['last_name'],
                'phone'       => phone($phoneNumberValue)->formatE164(), 
                'email'       => $row['email'],
                'address'     => json_encode([
                    'street'        => $row['street'] ?? null,
                    'city'         => $row['city'] ?? null,
                    'state'         => $row['state'] ?? null,
                    'zip'           => $row['zip'] ?? null,
                    'country'       => $row['country'] ?? null
                ]),
                'contact_group_id' => $group ? $group->id : null,
                'metadata'    => !empty($metadata) ? json_encode($metadata) : null, 
                'created_by'  => auth()->user()->id,
            ]);

            if($contact) {
                $this->successfulImports++;
                foreach ($this->selectedContactLabelIds as $key => $label_id) {
                    ContactLabelMember::updateOrInsert([
                        'contact_id' => $contact->id,
                        'contact_label_id' => $label_id
                    ]);
                }
                return $contact;
            }
        } catch (\Exception $e) {
            \Log::error('Error importing contact: ' . $e->getMessage(), [
                'row' => $row,
                'exception' => $e,
            ]);

            $this->failedImportsDueToFormat++;
            return null;
        }
    }

    public function getFailedImportsDueToDuplicatesCount()
    {
        return $this->failedImportsDueToDuplicates;
    }

    public function getFailedImportsDueToFirstName()
    {
        return $this->failedImportsDueToFirstName;
    }

    public function getFailedImportsDueToFormat()
    {
        return $this->failedImportsDueToFormat;
    }

    public function getSuccessfulImports()
    {
        return $this->successfulImports;
    }

    public function getTotalImportsCount()
    {
        return $this->totalImports;
    }

    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size as needed
    }
}


