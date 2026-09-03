<?php

namespace Tests\Feature;

use App\Models\ApprovedFormulation;
use App\Models\User;
use App\Models\UserWasteStock;
use App\Services\RecommendationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationEngineTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommendation_engine_evaluates_stock_readiness_correctly()
    {
        $user = User::factory()->create(['role_type' => 'Household']);

        // Create formulation requiring 1kg Banana Peels and 0.5kg Eggshells
        $formulation = ApprovedFormulation::create([
            'title' => 'Test Potassium Formula',
            'target_waste_type' => 'Banana Peels',
            'required_ingredients' => [
                ['waste_type' => 'Banana Peels', 'quantity' => 1.0, 'unit' => 'kg'],
                ['waste_type' => 'Eggshells', 'quantity' => 0.5, 'unit' => 'kg'],
            ],
            'yield_quantity' => 2.5,
            'yield_unit' => 'L',
            'fermentation_days' => 7,
            'npk_ratio' => '0-1-5',
            'primary_nutrients' => 'Potassium (K), Calcium (Ca)',
            'preparation_steps' => 'Chop and soak.',
            'application_guidance' => 'Apply to soil.',
        ]);

        $service = new RecommendationService();

        // 1. Initial state: zero stock -> insufficient
        $eval1 = $service->evaluateStockForUser($user);
        $this->assertEquals('insufficient', $eval1->first()['status']);

        // 2. Partial stock: 1kg Banana Peels, 0.1kg Eggshells -> partial
        UserWasteStock::create(['user_id' => $user->id, 'waste_type' => 'Banana Peels', 'quantity' => 1.0, 'unit' => 'kg']);
        UserWasteStock::create(['user_id' => $user->id, 'waste_type' => 'Eggshells', 'quantity' => 0.1, 'unit' => 'kg']);

        $eval2 = $service->evaluateStockForUser($user);
        $this->assertEquals('partial', $eval2->first()['status']);
        $this->assertEquals(0, $eval2->first()['producible_batches']);

        // 3. Sufficient stock: 1.5kg Banana Peels, 0.6kg Eggshells -> ready
        UserWasteStock::where('user_id', $user->id)->where('waste_type', 'Eggshells')->update(['quantity' => 0.6]);

        $eval3 = $service->evaluateStockForUser($user);
        $this->assertEquals('ready', $eval3->first()['status']);
        $this->assertEquals(1, $eval3->first()['producible_batches']);
    }
}
