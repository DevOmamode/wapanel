<?php

namespace App\Http\Controllers\User;

use App\Exports\ContactLabelsExport;
use App\Imports\ContactLabelsImport;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Contact;
use App\Models\ContactLabel;
use App\Http\Resources\ContactLabelResource;
use App\Models\ContactLabelMember;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Excel;
use Validator;

class ContactLabelController extends BaseController
{
    private function getCurrentOrganizationId()
    {
        return session()->get('current_organization');
    }

    public function index(Request $request, $uuid = null)
    {
        if($uuid === 'export') {
            return Excel::download(new ContactLabelsExport, 'contact-labels.xlsx');
        } else {
            $organizationId = $this->getCurrentOrganizationId();
            $contactLabelModel = new ContactLabel;

            $searchTerm = $request->query('search');
            $uuid = $request->query('id');
            $contactModel = new Contact;
            $rows = $contactLabelModel->getAll($organizationId, $searchTerm);
            $rowCount = $contactLabelModel->countAll($organizationId);
            $label = $contactLabelModel->getRow($uuid, $organizationId);
            $allContactLabels = $contactModel->getAllContactLabels($organizationId);
            $contactLabelIds = $label ? ContactLabelMember::where('contact_label_id', $label->id)->pluck('contact_id') : [];
            if ($label) {
                $label->contact_count = count($contactLabelIds);
            }
            $contact = Contact::where('organization_id',$organizationId)->get();
            return Inertia::render('User/Contact/Label', [
                'title' => __('Labels'),
                'rows' => ContactLabelResource::collection($rows),
                'rowCount' => $rowCount,
                'label' => $label,
                'contactLabelIds' => $contactLabelIds,
                'contact' => $contact,
                'allContactLabels' => $allContactLabels,
                'filters' => request()->all()
            ]);
        }
    }

    public function import(Request $request) 
    {
        $import = new ContactLabelsImport();
        Excel::import($import, $request->file);

        $successfulImports = $import->getsuccessfulImports();
        
        return redirect('/contact-labels')->with(
            'status', [
                'type' => $successfulImports > 0 ? 'success' : 'error', 
                'message' => $successfulImports > 0 ? __('Excel import successful!') : __('Excel import failed!'),
                'successfulImports' => $import->getsuccessfulImports(),
                'failedDuplicates' => $import->getFailedImportsDueToDuplicatesCount(),
                'failedFormats' => $import->getFailedImportsDueToFormat(),
                'totalImports' => $import->getTotalImportsCount(),
            ]
        );
    }

    public function store(Request $request)
    {
        $contactLabel = new ContactLabel();
        $contactLabel->organization_id = $this->getCurrentOrganizationId();
        $contactLabel->name = $request->name;
        $contactLabel->created_at = now();
        $contactLabel->updated_at = now();
        $contactLabel->save();

        return response()->json(['success' => true, 'message'=> __('Contact label added successfully'), 'data' => $contactLabel]);
    }

    public function update(Request $request, $uuid)
    {

        $contactLabel = ContactLabel::where('uuid', $uuid)->firstOrFail();
        $contactLabel->name = $request->name;
        $contactLabel->updated_at = now();
        $contactLabel->save();

        return response()->json(['success' => true, 'message'=> __('Contact label updated successfully'), 'data' => $contactLabel]);
    }

    public function delete(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $organizationId = session()->get('current_organization');

        if (empty($uuids)) {
            ContactLabel::whereNotNull('id')->where('organization_id', $organizationId)->delete();
        } else {

            ContactLabel::whereIn('uuid', $uuids)->where('organization_id', $organizationId)->delete();
        }

        return redirect('/contact-labels')->with(
            'status', [
                'type' => 'success',
                'message' => __('Label(s) deleted successfully')
            ]
        );
    }

    public function contactStore(Request $request) {
        $labelId = $request->labelId;
        $contactLabelIds = $request->contactLabelIds;
        ContactLabelMember::where('contact_label_id', $labelId)->whereNotIn('contact_id', $contactLabelIds)->delete();
        foreach ($contactLabelIds as $key => $id) {
            ContactLabelMember::updateOrInsert([
                'contact_id' => $id,
                'contact_label_id' => $labelId
            ],[
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function bulkContactStore(Request $request) {
        $selectedContacts = $request->selectedContacts;
        $selectedContactIds = Contact::whereIn('uuid', $selectedContacts)->pluck('id');
        $selectedLabels = $request->selectedLabels;
        foreach ($selectedContactIds as $key => $contact_id) {
            foreach ($selectedLabels as $key => $label_id) {
                ContactLabelMember::updateOrInsert([
                    'contact_id' => $contact_id,
                    'contact_label_id' => $label_id
                ]);
            }
        }
    }
}