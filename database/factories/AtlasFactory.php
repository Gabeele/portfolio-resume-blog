<?php

namespace Database\Factories;

use App\Models\Atlas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AtlasFactory extends Factory
{
    protected $model = Atlas::class;

    public function definition(): array
    {
        return [
            'bio' => $this->faker->word(),
            'template' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}
