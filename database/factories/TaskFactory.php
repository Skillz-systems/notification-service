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

            'user_id' => $this->faker->randomNumber(5, true),
            'title' => $this->faker->sentence(4),
            'for' => $this->faker->randomElement(['staff', 'customer', 'supplier', 'other']),
            'status' => $this->faker->randomElement(['visible', 'hidden', 'completed', 'staled']),
            'content' => $this->faker->paragraph(2),
            'user_email' => $this->faker->email(),
            'url' => $this->faker->url,
        ];

    }
}