<?php

namespace Tests\Feature;

use App\Models\Experiment;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantExperimentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_user_can_initialize_experiment_and_log_weekly_metrics()
    {
        $user = User::factory()->create(['role_type' => 'Household']);
        $user->assignRole('Household');

        $experiment = Experiment::create([
            'user_id' => $user->id,
            'plant_species' => 'Red Radish',
            'fertilizer_type' => 'Organic',
            'start_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($user)->post(route('household.experiments.measurements.store', $experiment->experiment_id), [
            'week_number' => 1,
            'plant_height_cm' => 5.4,
            'soil_pH' => 6.5,
            'leaf_vitality' => 'Healthy Green',
        ]);

        $response->assertRedirect(route('household.experiments.show', $experiment->experiment_id));
        $this->assertDatabaseHas('growth_measurements', [
            'experiment_id' => $experiment->experiment_id,
            'week_number' => 1,
            'plant_height_cm' => 5.4,
            'soil_pH' => 6.5,
            'leaf_vitality' => 'Healthy Green',
        ]);
    }
}
