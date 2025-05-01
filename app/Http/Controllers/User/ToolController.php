<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Services\WhatsAppWidgetService;
use Inertia\Inertia;

class ToolController extends BaseController
{
    private $widgetService;

    public function __construct(whatsAppWidgetService $widgetService)
    {
        $this->widgetService = $widgetService;
    }

    public function index()
    {
        return Inertia::render('User/Tools/Index', [
            'title' => __('Tools')
        ]);
    }

    public function whatsAppWidget()
    {
        $widget = $this->widgetService->index();
        return Inertia::render('User/Tools/WhatsAppWidget', [
            'widget' => $widget
        ]);
    }

    public function saveWhatsAppWidget(Request $request)
    {
        $this->widgetService->store($request);
    }

    public function uploadProfileImage(Request $request)
    {
        $this->widgetService->uploadProfileImage($request);
    }
}