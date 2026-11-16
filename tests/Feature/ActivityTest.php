<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Pet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_activity_can_be_logged_for_a_pet(): void
    {
        $pet = Pet::factory()->create();

        $response = $this->post(route('activities.store'), [
            'pet_id' => $pet->id,
            'activity_type' => 'Morning Walk',
            'activity_date' => today()->toDateString(),
            'description' => 'Park loop.',
        ]);

        $activity = Activity::first();
        $response->assertRedirect(route('activities.show', $activity));
        $this->assertTrue($activity->pet->is($pet));
        // Stored as a plain date so it compares correctly in SQLite.
        $this->assertDatabaseHas('activities', ['id' => $activity->id, 'activity_date' => today()->toDateString()]);
    }

    public function test_validation_rejects_missing_pet_and_out_of_range_dates(): void
    {
        $this->post(route('activities.store'), [
            'pet_id' => 999,
            'activity_type' => '',
            'activity_date' => now()->addYears(2)->toDateString(),
        ])->assertSessionHasErrors(['pet_id', 'activity_type', 'activity_date']);

        $this->assertDatabaseCount('activities', 0);
    }

    public function test_only_validated_fields_are_saved(): void
    {
        $pet = Pet::factory()->create();

        $this->post(route('activities.store'), [
            'pet_id' => $pet->id,
            'activity_type' => 'Feeding',
            'activity_date' => today()->toDateString(),
            'created_at' => '2000-01-01 00:00:00',
        ]);

        $this->assertNotSame('2000', Activity::first()->created_at->format('Y'));
    }

    public function test_an_activity_can_be_updated_and_deleted(): void
    {
        $activity = Activity::factory()->create(['activity_type' => 'Bath']);

        $this->patch(route('activities.update', $activity), [
            'pet_id' => $activity->pet_id,
            'activity_type' => 'Grooming',
            'activity_date' => $activity->activity_date->toDateString(),
        ])->assertRedirect(route('activities.show', $activity));
        $this->assertSame('Grooming', $activity->fresh()->activity_type);

        $this->delete(route('activities.destroy', $activity))->assertRedirect(route('activities.index'));
        $this->assertModelMissing($activity);
    }

    public function test_list_can_be_filtered_by_pet_type_and_search(): void
    {
        $biscuit = Pet::factory()->create(['name' => 'Biscuit']);
        $luna = Pet::factory()->create(['name' => 'Luna']);
        Activity::factory()->for($biscuit)->create(['activity_type' => 'Morning Walk', 'description' => 'canal path']);
        Activity::factory()->for($biscuit)->create(['activity_type' => 'Feeding', 'description' => 'usual']);
        Activity::factory()->for($luna)->create(['activity_type' => 'Feeding', 'description' => 'new food']);

        $count = fn (array $query) => $this->get(route('activities.index', $query))->viewData('activities')->total();

        $this->assertSame(3, $count([]));
        $this->assertSame(2, $count(['pet' => $biscuit->id]));
        $this->assertSame(2, $count(['type' => 'Feeding']));
        $this->assertSame(1, $count(['pet' => $luna->id, 'type' => 'Feeding']));
        $this->assertSame(1, $count(['search' => 'canal']));
    }

    public function test_create_page_redirects_to_add_a_pet_when_there_are_none(): void
    {
        $this->get(route('activities.create'))->assertRedirect(route('pets.create'));
    }

    public function test_dashboard_separates_recent_and_planned_activities(): void
    {
        $pet = Pet::factory()->create();
        Activity::factory()->for($pet)->create(['activity_type' => 'Feeding', 'activity_date' => today()]);
        Activity::factory()->for($pet)->create(['activity_type' => 'Vet Visit', 'activity_date' => today()->addWeek()]);

        $response = $this->get(route('dashboard'))->assertOk();

        $this->assertSame(['Feeding'], $response->viewData('recent')->pluck('activity_type')->all());
        $this->assertSame(['Vet Visit'], $response->viewData('upcoming')->pluck('activity_type')->all());
        $this->assertSame(1, $response->viewData('thisWeek'));
    }

    public function test_every_page_renders(): void
    {
        $activity = Activity::factory()->create();

        foreach ([
            route('dashboard'), route('about'), route('pets.index'), route('pets.create'),
            route('pets.show', $activity->pet), route('pets.edit', $activity->pet),
            route('activities.index'), route('activities.create'),
            route('activities.show', $activity), route('activities.edit', $activity),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }
}
