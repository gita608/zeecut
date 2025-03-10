<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UsersFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->name(),
            'email'      => $this->faker->unique()->safeEmail(),
            'phone'      => $this->faker->phoneNumber(),
            'role_id'    => 2, // Ensure it matches the DB schema
            'password'   => Hash::make('password123'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
