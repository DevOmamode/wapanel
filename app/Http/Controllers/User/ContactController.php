<?php

namespace App\Http\Controllers\User;

use DB;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Contact;
use App\Models\ContactField;
use App\Models\ContactGroup;
use App\Models\Organization;
use App\Exports\ContactsExport;
use App\Http\Requests\StoreContact;
use App\Http\Resources\ContactResource;
use App\Imports\ContactsImport;
use App\Models\ContactLabelMember;
use App\Services\ContactFieldService;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Excel;
use Validator;

class ContactController extends BaseController
{
    private function contactService()
    {
        return new ContactService(session()->get('current_organization'));
    }

    private function getCurrentOrganizationId()
    {
        return session()->get('current_organization');
    }

    public function index(Request $request, $uuid = null){
        $organizationId = $this->getCurrentOrganizationId();
        $contactModel = new Contact;

        if($uuid === 'export') {
            return Excel::download(new ContactsExport, 'contacts.xlsx');
        } else {
            $searchTerm = $request->query('search');
            $labelState = $request->query('status') == null ? 'all' : $request->query('status');
            $splitedLabelState = explode(',',$labelState);
            $uuid = $request->query('id') ? $request->query('id') : $uuid ;
            $editContact = $request->query('edit') === 'true' ? true : false;
            $contacts = $contactModel->getAllContacts($organizationId, $searchTerm,$splitedLabelState);
            $rowCount = $contactModel->countContacts($organizationId,$searchTerm,$splitedLabelState);
            $contactGroups = $contactModel->getAllContactGroups($organizationId);
            $allContactLabels = $contactModel->getAllContactLabels($organizationId);
            $contact = $uuid ? Contact::with(['contactGroup','contactLabelMemebers'])->where('uuid', $uuid)->where('deleted_at', null)->first() : null;
            $contactFields = ContactField::where('organization_id', $organizationId)->where('deleted_at', null)->get();
            // $contactLabelMembers = $contact ? ContactLabelMember::select('contact_label_members.contact_label_id','cl.name')->where('contact_label_members.contact_id', $contact->id)->leftJoin('contact_labels as cl', 'cl.id', 'contact_label_members.contact_label_id')->get() : [];
            $contactLabelIds = [];
            $contactLabels = null;
            if(isset($contact->contactLabelMemebers)){
                foreach ($contact->contactLabelMemebers as $key => $contactLabelMember) {
                    array_push($contactLabelIds,$contactLabelMember->contact_label_id);
                    if(count($contact->contactLabelMemebers) - 1 == $key){
                        $contactLabels .= $contactLabelMember->name ;
                    }else{
                        $contactLabels .= $contactLabelMember->name . ',';
                    }
                }
            }
            return Inertia::render('User/Contact/Index', [
                'title' => __('Contacts'),
                'rows' => ContactResource::collection($contacts),
                'rowCount' => $rowCount,
                'contact' => $contact,
                'fields' => $contactFields,
                'contactGroups' => $contactGroups,
                'allContactLabels' => $allContactLabels,
                'contactLabelIds' => $contactLabelIds,
                'contactLabels' => $contactLabels,
                'filters' => request()->all(),
                'locationSettings' => $this->getLocationSettings(),
                'editContact' => $editContact,
                'labelState'=>$labelState
            ]);
        }
    }

    public function import(Request $request) 
    {
        $import = new ContactsImport();
        $import->selectedContactLabelIds = json_decode($request->selectedContactLabelIds);
        Excel::import($import, $request->file);
        $successfulImports = $import->getsuccessfulImports();

        return redirect('/contacts')->with(
            'status', [
                'type' => $successfulImports > 0 ? 'success' : 'error', 
                'message' => $successfulImports > 0 ? __('Excel import successful!') : __('Excel import failed!'),
                'successfulImports' => $import->getsuccessfulImports(),
                'failedNames' => $import->getFailedImportsDueToFirstName(),
                'failedDuplicates' => $import->getFailedImportsDueToDuplicatesCount(),
                'failedFormats' => $import->getFailedImportsDueToFormat(),
                'totalImports' => $import->getTotalImportsCount(),
            ]
        );
    }

    public function store(StoreContact $request){
        $contact = $this->contactService()->store($request);
        
        return redirect('/contacts?id=' . $contact->uuid)->with(
            'status', [
                'type' => 'success', 
                'message' => __('Contact added successfully!')
            ]
        );
    }

    public function update(StoreContact $request, $uuid)
    {
        $contact = $this->contactService()->store($request, $uuid);

        return redirect('/contacts/' . $contact->uuid)->with(
            'status', [
                'type' => 'success', 
                'message' => __('Contact updated successfully!')
            ]
        );
    }

    public function favorite(Request $request, $uuid)
    {
        $this->contactService()->favorite($request, $uuid);

        return redirect('/contacts/' . $uuid)->with(
            'status', [
                'type' => 'success', 
                'message' => __('Contact updated successfully!')
            ]
        );
    }

    public function delete(Request $request)
    {
        $uuids = $request->input('uuids', []);
        $this->contactService()->delete($uuids);

        return redirect('/contacts')->with(
            'status', [
                'type' => 'success', 
                'message' => __('Contact(s) deleted successfully')
            ]
        );
    }

    private function getLocationSettings(){
        // Retrieve the settings for the current organization
        $settings = Organization::where('id', session()->get('current_organization'))->first();

        if ($settings) {
            // Decode the JSON metadata column into an associative array
            $metadata = json_decode($settings->metadata, true);

            if (isset($metadata['contacts'])) {
                // If the 'contacts' key exists, retrieve the 'location' value
                $location = $metadata['contacts']['location'];

                // Now, you have the location value available
                return $location;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}