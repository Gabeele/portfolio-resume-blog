<?php

namespace Database\Factories;

use App\Models\Education;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EducationFactory extends Factory
{
    protected $model = Education::class;

    public function definition(): array
    {
        return [
            'school' => $this->faker->word(),
            'certificate' => $this->faker->word(),
            'start' => Carbon::now(),
            'end' => Carbon::now(),
            'description' => $this->faker->text(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
