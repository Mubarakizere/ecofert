<?php

namespace Tests\Feature;

use App\Models\ApprovedFormulation;
use App\Models\User;
use App\Models\UserWasteStock;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FertilizerBatchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_user_can_start_fertilizer_batch_and_deduct_stock()
    {
        $user = User::factory()->create(['role_type' => 'Household']);
        $user->assignRole('Household');

        $formulation = ApprovedFormulation::create([
            'title' => 'Test Formula',
            'target_waste_type' => 'Banana Peels',
            'required_ingredients' => [
                ['waste_type' => 'Banana Peels', 'quantity' => 1.0, 'unit' => 'kg'],
            ],
            'yield_quantity' => 2.0,
            'yield_unit' => 'L',
            'fermentation_days' => 7,
            'preparation_steps' => 'Mix water and peels.',
            'application_guidance' => 'Apply weekly.',
        ]);

        UserWasteStock::create([
            'user_id' => $user->id,
            'waste_type' => 'Banana Peels',
            'quantity' => 3.0,
            'unit' => 'kg',
        ]);

        $response = $this->actingAs($user)->post(route('household.batches.store'), [
            'formulation_id' => $formulation->formula_id,
            'batch_count' => 2,
        ]);

        $response->assertRedirect(route('household.batches.index'));
        $this->assertDatabaseHas('fertilizer_batches', [
            'user_id' => $user->id,
            'formulation_id' => $formulation->formula_id,
            'status' => 'aging',
        ]);

        // 3.0kg stock minus 2*1.0kg = 1.0kg remaining
        $this->assertDatabaseHas('user_waste_stocks', [
            'user_id' => $user->id,
            'waste_type' => 'Banana Peels',
            'quantity' => 1.0,
        ]);
    }
}
