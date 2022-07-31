<?php

namespace Database\Factories;

use App\Models\Buildings;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Floors>
 */
class FloorsFactory extends Factory
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
            'building_id' => Buildings::factory()->create()->id,
            'floor_num' => $this->faker->randomNumber(1),
            'is_closed' => FALSE,
        ];
    }
}
