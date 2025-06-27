<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Upload;
use App\Models\MobileGroup;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_contacts' => Contact::count(),
            'main_contacts' => Contact::where('source', 'main')->count(),
            'mobile_contacts' => Contact::whereNotNull('mobile')->count(),
            'recent_uploads' => Upload::latest()->take(5)->with('uploader')->get(),
            'mobile_groups' => MobileGroup::count(),
            
            // Neue Benutzerstatistiken
            'total_users' => User::count(),
            'admin_users' => User::role('admin')->count(),
            'regular_users' => User::role('user')->count(),
            'recent_users' => User::latest()->take(5)->get(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}