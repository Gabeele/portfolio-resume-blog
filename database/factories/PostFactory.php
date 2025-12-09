<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'body' => $this->faker->words(),
            'publish' => $this->faker->boolean(),
            'tags' => $this->faker->word(),
            'meta_title' => $this->faker->word(),
            'meta_description' => $this->faker->text(),
            'meta_keywords' => $this->faker->word(),
            'published_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'user_id' => User::factory(),
        ];
    }
}

