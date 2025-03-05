<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'thumbnail' => $this->faker->imageUrl(),
            'created_by' => \App\Models\User::factory(), // Assuming a user exists
        ];
    }
}
