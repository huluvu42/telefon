<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'view_admin_dashboard',
            'manage_contacts',
            'upload_files',
            'delete_contacts',
            'sync_mobile_data'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        
        // Assign permissions to admin
        $adminRole->givePermissionTo($permissions);
        
        // Assign limited permissions to user
        $userRole->givePermissionTo(['view_admin_dashboard']);
    }
}