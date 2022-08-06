<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Roles>
 */
class RolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'role' => $this->faker->firstName(),
            'num_monthly_bookings' => $this->faker->numberBetween(1, 9),
            'max_booking_window' => $this->faker->numberBetween(1, 41),
            'max_booking_duration' => $this->faker->numberBetween(1, 41),
        ];
    }
}
