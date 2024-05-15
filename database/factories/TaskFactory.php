<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
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
            'id' => $this->faker->unique()->randomNumber(5, true),
            'processflow_history_id' => $this->faker->nullable()->numberBetween(1, 1000),
            'formbuilder_data_id' => $this->faker->nullable()->numberBetween(1, 1000),
            'entity_id' => $this->faker->nullable()->numberBetween(1, 1000),
            'entity_type' => $this->faker->nullable()->randomElement(['customer', 'supplier']),
            'user_id' => $this->faker->nullable()->numberBetween(1, 1000),
            'processflow_id' => $this->faker->nullable()->numberBetween(1, 1000),
            'processflow_step_id' => $this->faker->nullable()->numberBetween(1, 1000),
            'title' => $this->faker->sentence(),
            'route' => $this->faker->url(),
            'start_time' => $this->faker->nullable()->date(),
            'end_time' => $this->faker->nullable()->date(),
            'task_status' => $this->faker->randomElement([0, 1]),

        ];

    }

}