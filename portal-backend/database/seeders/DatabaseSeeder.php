<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin account
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@portalnews.id',
            'password' => bcrypt('Admin123!'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Run other seeders
        $this->call([
            CategorySeeder::class,
            PageSeeder::class,
            ArticleSeeder::class,
            ActivityLogSeeder::class,
        ]);

        // Optional: Create additional test users
        // User::factory(10)->create();
    }
}
