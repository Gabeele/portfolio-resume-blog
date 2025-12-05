<?php

namespace Database\Factories;

use App\Models\Atlas;
use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'url' => $this->faker->url(),
            'icon' => $this->faker->word(),
            'description' => $this->faker->text(),
            'order' => $this->faker->randomNumber(),
            'is_active' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'atlas_id' => Atlas::factory(),
        ];
    }
}
