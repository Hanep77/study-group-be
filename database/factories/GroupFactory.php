<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'deadline' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => 'active',
        ];
    }
}
