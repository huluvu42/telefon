<?php

// app/Livewire/Admin/ContactManager.php
namespace App\Livewire\Admin;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManager extends Component
{
    use WithPagination;
    
    public $search = '';
    public $editingContactId = null;
    public $name = '';
    public $first_name = '';
    public $title = '';
    public $phone = '';
    public $mobile = '';
    public $fax = '';
    public $email = '';
    public $building = '';
    public $department = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'title' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:50',
        'mobile' => 'nullable|string|max:50',
        'fax' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'building' => 'nullable|string|max:100',
        'department' => 'nullable|string|max:100'
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function editContact($contactId)
    {
        $contact = Contact::findOrFail($contactId);
        $this->editingContactId = $contactId;
        $this->name = $contact->name;
        $this->first_name = $contact->first_name;
        $this->title = $contact->title;
        $this->phone = $contact->phone;
        $this->mobile = $contact->mobile;
        $this->fax = $contact->fax;
        $this->email = $contact->email;
        $this->building = $contact->building;
        $this->department = $contact->department;
    }
    
    public function updateContact()
    {
        $this->validate();
        
        $contact = Contact::findOrFail($this->editingContactId);
        $contact->update([
            'name' => $this->name,
            'first_name' => $this->first_name,
            'title' => $this->title,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'fax' => $this->fax,
            'email' => $this->email,
            'building' => $this->building,
            'department' => $this->department
        ]);
        
        $this->resetForm();
        session()->flash('message', 'Contact updated successfully.');
    }
    
    public function deleteContact($contactId)
    {
        Contact::findOrFail($contactId)->delete();
        session()->flash('message', 'Contact deleted successfully.');
    }
    
    public function cancelEdit()
    {
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->editingContactId = null;
        $this->name = '';
        $this->first_name = '';
        $this->title = '';
        $this->phone = '';
        $this->mobile = '';
        $this->fax = '';
        $this->email = '';
        $this->building = '';
        $this->department = '';
    }
    
    public function render()
    {
        $contacts = Contact::query()
            ->search($this->search)
            ->orderBy('name')
            ->orderBy('first_name')
            ->paginate(20);
            
        return view('livewire.admin.contact-manager', ['contacts' => $contacts]);
    }
}