<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'category_id' => Category::factory(), // Generate a category for each course
            'price' => $this->faker->randomFloat(2, 10, 500),
            'duration' => $this->faker->numberBetween(1, 30), // Duration in days
            'thumbnail' => $this->faker->imageUrl(),
            'meta_keywords' => $this->faker->words(5, true),
            'meta_description' => $this->faker->paragraph(),
            'is_free_course' => $this->faker->boolean(),
            'features' => json_encode($this->faker->words(5)), // Dummy features as JSON
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'created_by' => \App\Models\User::factory(), // Assuming a user exists
            'updated_by' => \App\Models\User::factory(), // Assuming a user exists
        ];
    }
}
