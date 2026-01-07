<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * 
     * This seeder updates or creates the admin user with specified credentials.
     */
    public function run(): void
    {
        // Update existing admin or create new one
        $user = User::where('email', 'admin@gmail.com')->first();
        
        if ($user) {
            $user->update([
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Admin user updated successfully!');
        } else {
            // Check if there's an existing user with ID 1
            $existingUser = User::find(1);
            
            if ($existingUser) {
                $existingUser->update([
                    'name' => 'Administrator',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'super_admin',
                    'email_verified_at' => now(),
                ]);
                $this->command->info('Existing admin user (ID 1) updated with new credentials!');
            } else {
                User::create([
                    'name' => 'Administrator',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'super_admin',
                    'email_verified_at' => now(),
                ]);
                $this->command->info('Admin user created successfully!');
            }
        }

        $this->command->info('');
        $this->command->info('===========================================');
        $this->command->info('  ADMIN LOGIN CREDENTIALS');
        $this->command->info('===========================================');
        $this->command->info('  Email:    admin@gmail.com');
        $this->command->info('  Password: password');
        $this->command->info('===========================================');
    }
}
