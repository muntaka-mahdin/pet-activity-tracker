<?php

namespace Database\Factories;

use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Pet> */
class PetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'species' => fake()->randomElement(Pet::SPECIES),
            'breed' => fake()->optional()->word(),
            'date_of_birth' => fake()->optional()->dateTimeBetween('-12 years', '-2 months')?->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
