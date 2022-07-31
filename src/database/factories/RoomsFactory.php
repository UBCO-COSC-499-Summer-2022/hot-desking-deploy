<?php

namespace Database\Factories;

use App\Models\Floors;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rooms>
 */
class RoomsFactory extends Factory
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
            'floor_id' => Floors::factory()->create()->id,
            'name' => $this->faker->firstName(),
            'occupancy' => $this->faker->numberBetween(1, 40),
            'is_closed' => FALSE,
        ];
    }
}
