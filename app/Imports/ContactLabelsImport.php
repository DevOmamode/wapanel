<?php

namespace App\Imports;

use App\Models\ContactLabel;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;

class ContactLabelsImport implements ToModel, WithHeadingRow
{
    protected $successfulImports = 0;
    protected $totalImports = 0;
    protected $failedImportsDueToFormat = 0;
    protected $failedImportsDueToDuplicates = 0;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            $this->totalImports++;
            $validator = Validator::make($row, [
                'group_name' => [
                    'required',
                    function ($attribute, $value, $fail) use ($row) {
                        if (ContactLabel::where('organization_id', session()->get('current_organization'))->where('name', $row['group_name'])) {
                            $this->failedImportsDueToDuplicates++;
                            $fail('The '.$attribute.' already exists.');
                        }
                    }
                ]
            ]);

            if ($validator->fails()) {
                return null;
            }
            
            $contactLabel = new ContactLabel([
                'organization_id'  => session()->get('current_organization'),
                'name'  => $row['group_name']
            ]);

            if($contactLabel){
                $this->successfulImports++;
                return $contactLabel;
            }
        } catch (\Exception $e) {
            $this->failedImportsDueToFormat++;
            
            return null;
        }
    }

    public function getFailedImportsDueToDuplicatesCount()
    {
        return $this->failedImportsDueToDuplicates;
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
}


