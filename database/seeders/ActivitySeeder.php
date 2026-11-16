<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Pet;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /** Three demo pets with a month of activity each, plus a couple of planned vet visits. */
    public function run(): void
    {
        $pets = [
            Pet::create(['name' => 'Biscuit', 'species' => 'Dog', 'breed' => 'Cocker Spaniel',
                'date_of_birth' => now()->subYears(4)->subMonths(3), 'notes' => 'Allergic to chicken.']),
            Pet::create(['name' => 'Luna', 'species' => 'Cat', 'breed' => 'British Shorthair',
                'date_of_birth' => now()->subYears(2)->subMonths(7)]),
            Pet::create(['name' => 'Pepper', 'species' => 'Rabbit', 'breed' => 'Netherland Dwarf',
                'date_of_birth' => now()->subMonths(10), 'notes' => 'Needs hay topped up daily.']),
        ];

        $typesFor = [
            'Dog' => ['Morning Walk', 'Evening Walk', 'Feeding', 'Training', 'Playtime', 'Grooming', 'Bath'],
            'Cat' => ['Feeding', 'Playtime', 'Grooming', 'Medication'],
            'Rabbit' => ['Feeding', 'Playtime', 'Grooming'],
        ];

        $notes = [
            'Morning Walk' => ['Park loop, about 40 minutes.', 'Short walk, it was raining.', 'Met two other dogs, very excited.'],
            'Evening Walk' => ['Around the block.', 'Long walk by the canal.'],
            'Feeding' => ['Usual portion.', 'Left some food, keep an eye on it.', 'Tried the new food, ate it all.'],
            'Training' => ['Practised recall, getting better.', 'Worked on "stay" for 10 minutes.'],
            'Playtime' => [
                'Dog' => ['Played fetch in the garden.', 'Tug of war with the rope toy.'],
                'Cat' => ['Chased the feather toy.', 'Batted the ball around the hallway.'],
                'Rabbit' => ['Explored the run.', 'Zoomies round the living room.'],
            ],
            'Grooming' => [
                'Dog' => ['Brushed coat and checked ears.', 'Trimmed claws.'],
                'Cat' => ['Brushed out loose fur.'],
                'Rabbit' => ['Trimmed claws.', 'Gentle brush, shedding a lot.'],
            ],
            'Bath' => ['Muddy after the walk, full bath.'],
            'Medication' => ['Flea treatment applied.', 'Worming tablet given with food.'],
        ];

        foreach ($pets as $pet) {
            for ($i = 0; $i < 14; $i++) {
                $type = fake()->randomElement($typesFor[$pet->species]);
                Activity::create([
                    'pet_id' => $pet->id,
                    'activity_type' => $type,
                    // Roughly one entry every two days over the last month.
                    'activity_date' => now()->subDays($i * 2 + fake()->numberBetween(0, 1))->toDateString(),
                    // Some notes depend on the animal (a rabbit doesn't play fetch).
                    'description' => fake()->randomElement($notes[$type][$pet->species] ?? $notes[$type]),
                ]);
            }
        }

        Activity::create(['pet_id' => $pets[0]->id, 'activity_type' => 'Vet Visit',
            'activity_date' => now()->addDays(9)->toDateString(), 'description' => 'Annual booster jabs.']);
        Activity::create(['pet_id' => $pets[1]->id, 'activity_type' => 'Vet Visit',
            'activity_date' => now()->addDays(23)->toDateString(), 'description' => 'Dental check.']);
    }
}
