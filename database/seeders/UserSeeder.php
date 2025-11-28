<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'first_name' => 'Test',
            'email' => 'test@example.com',
        ])->assignRole('Standard');

        User::factory()->create([
            'first_name' => 'Admin',
            'email' => 'admin@example.com',
        ])->assignRole('super_admin');
    }
}
