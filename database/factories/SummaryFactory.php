<?php

namespace Database\Factories;

use App\Models\Summary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SummaryFactory extends Factory
{
    protected $model = Summary::class;

    public function definition(): array
    {
        return [
            'body' => $this->faker->realText(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
