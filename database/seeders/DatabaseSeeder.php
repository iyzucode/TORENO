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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@toreno.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Kasir Utama',
            'email' => 'cashier@toreno.com',
            'password' => bcrypt('password'),
            'role' => 'cashier',
        ]);

        User::factory()->create([
            'name' => 'Dapur Utama',
            'email' => 'kitchen@toreno.com',
            'password' => bcrypt('password'),
            'role' => 'kitchen',
        ]);
    }
}
