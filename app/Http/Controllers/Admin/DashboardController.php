<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Upload;
use App\Models\MobileGroup;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_contacts' => Contact::count(),
            'main_contacts' => Contact::where('source', 'main')->count(),
            'mobile_contacts' => Contact::whereNotNull('mobile')->count(),
            'recent_uploads' => Upload::latest()->take(5)->with('uploader')->get(),
            'mobile_groups' => MobileGroup::count()
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}