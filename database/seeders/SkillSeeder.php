<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        Skill::factory(10)->create([
            'user_id' => User::where('email', 'test@example.com')->first()->id
        ]);
    }
}
