<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'created_by' => User::factory(),
            'assigned_to' => User::factory(),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => $this->faker->dateTimeBetween('now', '+1 week'),
        ];
    }
}
