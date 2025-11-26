<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class WorkExperienceFactory extends Factory
{
    protected $model = WorkExperience::class;

    public function definition(): array
    {
        return [
            'business' => $this->faker->word(),
            'role' => $this->faker->word(),
            'description' => $this->faker->text(),
            'start' => Carbon::now(),
            'end' => Carbon::now(),
            'location' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
