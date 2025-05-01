<?php

namespace App\Exports;

use App\Models\ContactLabel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactLabelsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $contactlabels = ContactLabel::where('organization_id', session()->get('current_organization'))
            ->get();


        // Modify the collection to include formatted phone numbers and group names
        return $contactlabels->map(function ($list) {
            $row = [
                'list_name' => $list->name,
            ];

            return $row;
        });
    }

    public function headings(): array
    {
        // Define your headers here
        $headers = [
            'List name'
        ];

        return $headers;
    }
}
