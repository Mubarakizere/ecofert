<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Models\ApprovedFormulation;
use App\Services\AIService;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecommendationController extends Controller
{
    public function index(RecommendationService $recommendationService)
    {
        $user = Auth::user();
        $recommendations = $recommendationService->evaluateStockForUser($user);
        $nutrientMeters = $recommendationService->calculateNutrientPotential($user);

        return view('household.recommendations.index', compact('recommendations', 'nutrientMeters'));
    }

    public function explain(ApprovedFormulation $formulation, RecommendationService $recommendationService, AIService $aiService)
    {
        $user = Auth::user();
        $recommendations = $recommendationService->evaluateStockForUser($user)->keyBy(function ($item) {
            return $item['formulation']->formula_id;
        });

        $ruleMatch = $recommendations->get($formulation->formula_id) ?? [
            'status' => 'ready',
            'producible_batches' => 1,
        ];

        $explanation = $aiService->explainFormulationMatch($formulation, $ruleMatch);

        return redirect()->route('household.recommendations.index')
            ->with('ai_explanation', [
                'formula_id' => $formulation->formula_id,
                'title' => $formulation->title ?? $formulation->target_waste_type,
                'explanation' => $explanation,
            ]);
    }
}
