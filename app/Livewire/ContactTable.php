<?php

// app/Livewire/ContactTable.php
namespace App\Livewire;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactTable extends Component
{
    use WithPagination;
    
    public $search = '';
    public $buildingFilter = '';
    public $departmentFilter = '';
    public $showMobile = true;
    public $perPage = 50;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'buildingFilter' => ['except' => ''],
        'departmentFilter' => ['except' => '']
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingBuildingFilter()
    {
        $this->resetPage();
    }
    
    public function updatingDepartmentFilter()
    {
        $this->resetPage();
    }
    
    public function toggleMobileColumn()
    {
        $this->showMobile = !$this->showMobile;
    }
    
    public function render()
    {
        $contacts = Contact::query()
            ->search($this->search)
            ->filterByBuilding($this->buildingFilter)
            ->filterByDepartment($this->departmentFilter)
            ->orderBy('name')
            ->orderBy('first_name')
            ->paginate($this->perPage);
            
        $buildings = Contact::select('building')
            ->distinct()
            ->whereNotNull('building')
            ->orderBy('building')
            ->pluck('building');
            
        $departments = Contact::select('department')
            ->distinct()
            ->whereNotNull('department')
            ->orderBy('department')
            ->pluck('department');
        
        return view('livewire.contact-table', [
            'contacts' => $contacts,
            'buildings' => $buildings,
            'departments' => $departments
        ]);
    }
}