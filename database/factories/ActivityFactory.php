<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Pet;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Activity> */
class ActivityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pet_id' => Pet::factory(),
            'activity_type' => fake()->randomElement(Activity::TYPES),
            'activity_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
