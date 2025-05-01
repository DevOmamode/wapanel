<?php

namespace App\Services;

use App\Models\WhatsAppWidget;
use Illuminate\Validation\ValidationException;
use Auth;
use Validator;
use Illuminate\Http\Request;

class WhatsAppWidgetService
{
    public function index()
    {
        $widget = WhatsAppWidget::where('user_id', Auth::id())->first();
        return $widget;
    }

    public function store(Request $request)
    {
        $files = scandir(public_path('uploads/whatsapp_widget'));
        $latestFile = [];
        $latestTime = 0;
        foreach ($files as $file) {
                $latestTime = filemtime(public_path("uploads/whatsapp_widget/$file"));
                $latestFile[] = $file;
        }
        $request->merge(['profileImage' => $latestFile[2]]);

        $validator = Validator::make($request->all(), [
            'businessName' => 'required',
            'phone' => 'required',
            'subHeading' => 'required',
            'profileImage' => 'required',
            'position' => 'required',
            'mainMessage' => 'required',
            'subMessage' => 'required',
            'buttonOne' => 'required'
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $checkWiget = WhatsAppWidget::where('user_id', Auth::id())->first();
        if($checkWiget){
            $checkWiget->delete();
        }

        $data = [
            'business_name' => $request->businessName,
            'whatsapp_number' => $request->phone,
            'status_message' => $request->subHeading,
            'profile_image' => $request->profileImage,
            'widget_position' => $request->position,
            'welcome_message' => $request->mainMessage,
            'secondary_message' => $request->subMessage,
            'button1' => $request->buttonOne,
            'button2' => isset($request->buttonTwo)?$request->buttonTwo:'',
            'button3' => isset($request->buttonThree)?$request->buttonThree:'',
            'user_id' => Auth::id(),
            'status' => 1
        ];

       $widget = WhatsAppWidget::create($data);

       return true;
    }

    public function uploadProfileImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'profile_image' => 'required'
        ]); 

        if ($validator->fails()) {  
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        if (!file_exists(public_path('uploads/whatsapp_widget'))) {     
            mkdir(public_path('uploads/whatsapp_widget'), 0777, true);
        }

        //$this->deleteOldFiles();

        $file = $request->file('profile_image');
        //$filename = 'profile_' . Auth::id() . '.' . $file->getClientOriginalExtension();
        $filename = 'widget-' . time() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads/whatsapp_widget'), $filename);

        echo $filename;
    }

    public function deleteOldFiles()
    {
        $folderPath = getcwd().'/uploads/whatsapp_widget'; 
        $files = glob($folderPath . '/*'); 

        foreach ($files as $file) {
            if (is_file($file)) { 
                unlink($file);
            }
        }
    }
};