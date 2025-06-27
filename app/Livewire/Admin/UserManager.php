<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;
    
    public $search = '';
    public $editingUserId = null;
    public $name = '';
    public $email = '';
    public $role = '';
    public $password = '';
    public $password_confirmation = '';
    
    protected $rules = [
        'name' => 'required|min:2',
        'email' => 'required|email',
        'role' => 'required'
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';
        $this->password = '';
        $this->password_confirmation = '';
    }
    
    public function updateUser()
    {
        $this->validate();
        
        $user = User::findOrFail($this->editingUserId);
        
        // Check if email is unique (excluding current user)
        $this->validate([
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);
        
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);
        
        // Update password if provided
        if (!empty($this->password)) {
            $this->validate([
                'password' => 'min:8|same:password_confirmation'
            ]);
            $user->update(['password' => bcrypt($this->password)]);
        }
        
        // Update role
        $user->syncRoles([$this->role]);
        
        session()->flash('message', 'Benutzer wurde erfolgreich aktualisiert.');
        $this->resetForm();
    }
    
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent deleting the last admin
        if ($user->hasRole('admin')) {
            $adminCount = User::role('admin')->count();
            if ($adminCount <= 1) {
                session()->flash('error', 'Der letzte Administrator kann nicht gelöscht werden.');
                return;
            }
        }
        
        // Prevent users from deleting themselves
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Sie können sich nicht selbst löschen.');
            return;
        }
        
        $user->delete();
        session()->flash('message', 'Benutzer wurde erfolgreich gelöscht.');
    }
    
    public function cancelEdit()
    {
        $this->resetForm();
    }
    
    private function resetForm()
    {
        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->role = '';
        $this->password = '';
        $this->password_confirmation = '';
    }
    
    public function render()
    {
        $users = User::with('roles')
            ->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(15);
            
        $roles = Role::all();
        
        return view('livewire.admin.user-manager', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}