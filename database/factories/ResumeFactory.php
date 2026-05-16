<?php

namespace Database\Factories;

use App\Enums\ResumeTemplate;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'tags' => $this->faker->word(),
            'template' => $this->faker->randomElement(ResumeTemplate::class),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
