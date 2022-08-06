<?php

namespace Database\Factories;

use App\Models\Resources;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ResourcesFactory extends Factory
{
    public function definition() 
    {
        return[
            'resource_type' => $this->faker->name(),
            'icon' => $this->faker->name(),
            'colour' => $this->faker->hexColor(),
        ];
    }
    
}