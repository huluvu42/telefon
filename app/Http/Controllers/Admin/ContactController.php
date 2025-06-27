<?php

// app/Http/Controllers/Admin/ContactController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('admin.contacts.index');
    }
    
    public function create()
    {
        return view('admin.contacts.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'building' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100'
        ]);
        
        $validated['source'] = 'manual';
        
        Contact::create($validated);
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact created successfully.');
    }
    
    public function edit(Contact $contact)
    {
        return view('admin.contacts.edit', compact('contact'));
    }
    
    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'building' => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100'
        ]);
        
        $contact->update($validated);
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact updated successfully.');
    }
    
    public function destroy(Contact $contact)
    {
        $contact->delete();
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}
