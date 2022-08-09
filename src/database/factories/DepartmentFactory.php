<?php

namespace Database\Factories;

use App\Models\Campuses;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Rooms;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Desks>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'faculty_id' => Faculty::factory()->create()->faculty_id,
            'department' =>  $this->faker->firstName(),    
        ];
    }
}