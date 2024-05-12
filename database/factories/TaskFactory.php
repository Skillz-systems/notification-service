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
            'owner_email' => $this->faker->email(),
            'title' => $this->faker->sentence(4),
            'for' => $this->faker->randomElement(['staff', 'customer', 'supplier', 'other']),
            'status' => $this->faker->randomElement(['visible', 'hidden', 'completed', 'staled']),
            'content' => $this->faker->paragraph(2),
            'user_email' => $this->faker->email(),
            'url' => $this->faker->url,
            'owner_id' => $this->faker->randomNumber(5, true),
            'due_at' => $this->faker->dateTimeBetween('+1 day', '+1 week'),
            // 'created_at' => $faker->dateTimeThisMonth(),
            // 'updated_at' => now(),

        ];

    }



    private function getRandomOwnerType()
    {
        $ownerTypes = [
            User::class,
            Customer::class,
            // Supplier::class,
        ];

        return $ownerTypes[array_rand($ownerTypes)];
    }

    public function forUser()
    {
        return $this->state(function (array $attributes) {
            return [
                'owner_id' => User::factory(),
                'owner_type' => User::class,
            ];
        });
    }

    public function forCustomer()
    {
        return $this->state(function (array $attributes) {
            return [
                'owner_id' => Customer::factory(),
                'owner_type' => Customer::class,
            ];
        });
    }

    // public function forSupplier()
    // {
    //     return $this->state(function (array $attributes) {
    //         return [
    //             'owner_id' => Supplier::factory(),
    //             'owner_type' => Supplier::class,
    //         ];
    //     });
    // }
}