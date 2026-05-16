<?php

namespace Database\Factories;

use App\Models\Reference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReferenceFactory extends Factory
{
    protected $model = Reference::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'title' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'company' => $this->faker->company(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
