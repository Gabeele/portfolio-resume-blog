<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkExperienceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        WorkExperience::factory(10)->create([
            'user_id' => User::where('email', 'test@example.com')->first()->id

        ]);
    }
}
