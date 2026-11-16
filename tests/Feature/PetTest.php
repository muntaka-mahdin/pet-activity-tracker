<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetTest extends TestCase
{
    use RefreshDatabase;

    public function test_pets_page_lists_pets_with_activity_counts(): void
    {
        $pet = Pet::factory()->create(['name' => 'Biscuit']);
        Activity::factory()->count(3)->for($pet)->create();

        $this->get(route('pets.index'))
            ->assertOk()
            ->assertSee('Biscuit')
            ->assertSee('3 activities');
    }

    public function test_a_pet_can_be_added(): void
    {
        $response = $this->post(route('pets.store'), [
            'name' => 'Luna',
            'species' => 'Cat',
            'breed' => 'British Shorthair',
            'date_of_birth' => '2023-04-01',
        ]);

        $pet = Pet::firstWhere('name', 'Luna');
        $response->assertRedirect(route('pets.show', $pet));
        $this->assertSame('2023-04-01', $pet->date_of_birth->toDateString());
    }

    public function test_species_must_be_from_the_list_and_birth_date_not_in_the_future(): void
    {
        $this->post(route('pets.store'), [
            'name' => 'Rex',
            'species' => 'Dragon',
            'date_of_birth' => now()->addDay()->toDateString(),
        ])->assertSessionHasErrors(['species', 'date_of_birth']);

        $this->assertDatabaseCount('pets', 0);
    }

    public function test_a_pet_can_be_updated(): void
    {
        $pet = Pet::factory()->create(['name' => 'Pepper']);

        $this->patch(route('pets.update', $pet), ['name' => 'Peppa', 'species' => 'Rabbit'])
            ->assertRedirect(route('pets.show', $pet));

        $this->assertSame('Peppa', $pet->fresh()->name);
    }

    public function test_deleting_a_pet_also_deletes_its_activities(): void
    {
        $pet = Pet::factory()->create();
        Activity::factory()->count(2)->for($pet)->create();
        $other = Activity::factory()->create();

        $this->delete(route('pets.destroy', $pet))->assertRedirect(route('pets.index'));

        $this->assertModelMissing($pet);
        $this->assertDatabaseCount('activities', 1);
        $this->assertModelExists($other);
    }

    public function test_pet_page_shows_only_that_pets_history(): void
    {
        $pet = Pet::factory()->create();
        Activity::factory()->for($pet)->create(['activity_type' => 'Grooming']);
        Activity::factory()->create(['activity_type' => 'Swimming lesson']);

        $this->get(route('pets.show', $pet))
            ->assertOk()
            ->assertSee('Grooming')
            ->assertDontSee('Swimming lesson');
    }

    public function test_age_label(): void
    {
        $this->assertSame('3 years', Pet::factory()->make(['date_of_birth' => now()->subYears(3)->subDays(5)])->ageLabel());
        $this->assertSame('5 months', Pet::factory()->make(['date_of_birth' => now()->subMonths(5)->subDays(2)])->ageLabel());
        $this->assertNull(Pet::factory()->make(['date_of_birth' => null])->ageLabel());
    }
}
