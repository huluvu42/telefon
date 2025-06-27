// app/Console/Commands/SetupPhonebook.php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;
use App\Models\User;

class SetupPhonebook extends Command
{
    protected $signature = 'phonebook:setup {--admin-email=} {--admin-password=}';
    protected $description = 'Setup the phonebook application with initial data';

    public function handle()
    {
        $this->info('Setting up Phonebook Application...');
        
        // Run migrations
        $this->info('Running migrations...');
        Artisan::call('migrate:fresh');
        
        // Seed roles and permissions
        $this->info('Seeding roles and permissions...');
        Artisan::call('db:seed', ['--class' => 'RolePermissionSeeder']);
        
        // Create admin user if credentials provided
        $adminEmail = $this->option('admin-email') ?: $this->ask('Admin email');
        $adminPassword = $this->option('admin-password') ?: $this->secret('Admin password');
        
        if ($adminEmail && $adminPassword) {
            $this->info('Creating admin user...');
            
            $admin = User::create([
                'name' => 'Administrator',
                'email' => $adminEmail,
                'email_verified_at' => now(),
                'password' => bcrypt($adminPassword)
            ]);
            
            $admin->assignRole('admin');
            
            $this->info("✅ Admin user created: {$adminEmail}");
        }
        
        // Create storage directories
        $this->info('Creating storage directories...');
        if (!file_exists(storage_path('app/public/uploads'))) {
            mkdir(storage_path('app/public/uploads'), 0755, true);
        }
        if (!file_exists(storage_path('app/public/mobile_lists'))) {
            mkdir(storage_path('app/public/mobile_lists'), 0755, true);
        }
        
        // Create symbolic link for storage
        Artisan::call('storage:link');
        
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