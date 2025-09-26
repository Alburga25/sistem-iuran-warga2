<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 20 user acak dengan data berbeda
        User::factory(20)->create();

        // Buat user admin tetap
        User::factory()->create([
            'name' => 'Admin Desa',
            'email' => 'admin@gmail.com',
            'noWa' => '081234567890',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $this->call([
            
        ]);
    }
}
