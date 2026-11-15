<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker; // I used it to generate random data

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        $activityTypes = [
            'Morning Walk',
            'Evening Walk',
            'Feeding',
            'Vet Visit',
            'Grooming',
            'Training',
            'Playtime',
            'Bath',
            'Medication',
            'Playdate',
        ];

        for ($i = 0; $i < 30; $i++) {
            DB::table('activities')->insert([
                'activity_type' => $faker->randomElement($activityTypes),
                'activity_date' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                'description'   => $faker->sentence(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
