<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CertificateSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Certificate::factory(10)->create([
            'user_id' => User::where('email', 'test@example.com')->first()->id

        ]);
    }
}
