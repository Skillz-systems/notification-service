<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->bigInteger(),
            'user_id' => $this->faker->bigInteger(),
            'user_email' => $this->faker->email(),
            'title' => $this->faker->sentence(4),
            'for' => $this->faker->randomElement(['staff', 'customer', 'supplier', 'other']),
            'status' => $this->faker->randomElement(['visible', 'hidden', 'completed', 'stalled']),
            'content' => $this->faker->paragraph(2),
            'url' => $this->faker->url,
            // 'created_at' => $faker->dateTimeThisMonth(),
            // 'updated_at' => now(),

        ];

    }
}