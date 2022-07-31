<?php

namespace Database\Factories;

use App\Models\Rooms;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Desks>
 */
class DesksFactory extends Factory
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
            'room_id' => Rooms::factory()->create()->id,
            'pos_x' => $this->faker->numberBetween(0, 1000),
            'pos_y' => $this->faker->numberBetween(0, 1000),
            'is_closed' => FALSE,
        ];
    }
}
