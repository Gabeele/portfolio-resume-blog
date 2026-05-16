<?php

namespace Database\Seeders;

use App\Models\Summary;
use App\Models\User;
use Illuminate\Database\Seeder;

class SummarySeeder extends Seeder
{
    public function run(): void
    {
        Summary::factory(10)->create([
            'user_id' => User::where('email', 'test@example.com')->first()->id
        ]);
    }
}
