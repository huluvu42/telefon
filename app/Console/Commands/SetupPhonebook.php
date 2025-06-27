<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class SetupPhonebook extends Command
{
    protected $signature = 'phonebook:setup {--admin-email=} {--admin-password=}';
    protected $description = 'Setup the phonebook application with initial data';

    public function handle()
    {
        $this->info('Setting up Phonebook Application...');
        
        // Check if database is accessible
        try {
            \DB::connection()->getPdo();
            $this->info('✅ Database connection successful');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
            return 1;
        }
        
        // Run migrations
        $this->info('Running migrations...');
        try {
            if ($this->confirm('This will reset all data. Continue?', true)) {
                Artisan::call('migrate:fresh', ['--force' => true]);
                $this->info('✅ Migrations completed');
            } else {
                Artisan::call('migrate');
                $this->info('✅ New migrations completed');
            }
        } catch (\Exception $e) {
            $this->error('❌ Migration failed: ' . $e->getMessage());
            return 1;
        }
        
        // Check if users table exists
        if (!Schema::hasTable('users')) {
            $this->error('❌ Users table still missing. Please check your migrations.');
            return 1;
        }
        
        // Create roles and permissions manually
        $this->info('Creating roles and permissions...');
        try {
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
                $this->info("✅ Permission created: {$permission}");
            }
            
            // Create roles
            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            $userRole = Role::firstOrCreate(['name' => 'user']);
            
            $this->info('✅ Roles created: admin, user');
            
            // Assign permissions to admin
            $adminRole->givePermissionTo($permissions);
            
            // Assign limited permissions to user
            $userRole->givePermissionTo(['view_admin_dashboard']);
            
            $this->info('✅ Permissions assigned to roles');
            
        } catch (\Exception $e) {
            $this->error('❌ Role/Permission creation failed: ' . $e->getMessage());
            return 1;
        }
        
        // Create admin user
        $adminEmail = $this->option('admin-email') ?: $this->ask('Admin email');
        $adminPassword = $this->option('admin-password') ?: $this->secret('Admin password');
        
        if ($adminEmail && $adminPassword) {
            $this->info('Creating admin user...');
            
            try {
                // Check if user already exists
                $existingUser = User::where('email', $adminEmail)->first();
                if ($existingUser) {
                    $this->warn("User with email {$adminEmail} already exists. Updating role.");
                    $existingUser->assignRole('admin');
                } else {
                    $admin = User::create([
                        'name' => 'Administrator',
                        'email' => $adminEmail,
                        'email_verified_at' => now(),
                        'password' => bcrypt($adminPassword)
                    ]);
                    
                    // Verify role exists before assigning
                    $adminRole = Role::where('name', 'admin')->first();
                    if ($adminRole) {
                        $admin->assignRole('admin');
                        $this->info("✅ Admin user created: {$adminEmail}");
                    } else {
                        $this->error('❌ Admin role not found');
                        return 1;
                    }
                }
            } catch (\Exception $e) {
                $this->error('❌ Admin user creation failed: ' . $e->getMessage());
                return 1;
            }
        }
        
        // Create storage directories
        $this->info('Creating storage directories...');
        $directories = [
            storage_path('app/public/uploads'),
            storage_path('app/public/mobile_lists')
        ];
        
        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
                $this->info("✅ Created directory: {$dir}");
            }
        }
        
        // Create symbolic link for storage
        try {
            Artisan::call('storage:link');
            $this->info('✅ Storage link created');
        } catch (\Exception $e) {
            $this->warn('⚠️  Storage link creation failed (may already exist): ' . $e->getMessage());
        }
        
        $this->info('✅ Phonebook setup completed successfully!');
        $this->newLine();
        $this->info('🚀 Next steps:');
        $this->info('1. Run: php artisan serve');
        $this->info('2. Visit: http://localhost:8000');
        $this->info('3. Login with your admin credentials');
        $this->info('4. Upload Excel files in the admin area');
        $this->newLine();
        $this->info('📝 LDAP can be added later with LdapRecord-Laravel if needed.');
        
        return 0;
    }
}