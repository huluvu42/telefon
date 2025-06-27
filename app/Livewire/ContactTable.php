<?php

namespace App\Livewire;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $perPage = 50;
    
    protected $queryString = [
        'search' => ['except' => '']
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function render()
    {
        $contacts = Contact::query()
            ->search($this->search)
            ->orderBy('name')
            ->orderBy('first_name')
            ->paginate($this->perPage);
        
        return view('livewire.contact-table', [
            'contacts' => $contacts
        ]);
    }
}