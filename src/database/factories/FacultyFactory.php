<?php

namespace Database\Factories;

use App\Models\Campuses;
use App\Models\Rooms;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Desks>
 */
class FacultyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'campus_id' => Campuses::factory()->create()->id,
            'faculty' =>  $this->faker->firstName(),
            
        ];
    }
}