<?php

namespace Database\Factories;

use App\Models\Desks;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookings>
 */
class BookingsFactory extends Factory
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
            'user_id' => User::factory()->create()->id,
            'desk_id' => Desks::factory()->create()->id,
            'book_time_start' => $this->faker->dateTimeThisMonth(),
            'book_time_end' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
