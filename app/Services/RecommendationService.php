<?php

namespace App\Services;

use App\Models\ApprovedFormulation;
use App\Models\User;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function evaluateStockForUser(User $user): Collection
    {
        $stocks = $user->wasteStocks()->get()->keyBy('waste_type');
        $formulations = ApprovedFormulation::all();

        return $formulations->map(function (ApprovedFormulation $formulation) use ($stocks) {
            $required = $formulation->required_ingredients ?? [];
            if (empty($required)) {
                // Fallback for simple single-waste formulations
                $required = [
                    ['waste_type' => $formulation->target_waste_type, 'quantity' => 1.0, 'unit' => 'kg']
                ];
            }

            $ingredientBreakdown = [];
            $missingItems = [];
            $maxPossibleBatches = PHP_INT_MAX;
            $totalCoveragePercent = 0;
            $ingredientCount = count($required);

            foreach ($required as $item) {
                $type = $item['waste_type'];
                $reqQty = (float) ($item['quantity'] ?? 1.0);

                $stockModel = $stocks->get($type);
                $availQty = $stockModel ? (float) $stockModel->quantity : 0.0;

                $missing = max(0, $reqQty - $availQty);
                $ratio = $reqQty > 0 ? ($availQty / $reqQty) : 0;
                $coverage = min(100, (int) round($ratio * 100));

                $possibleForThisItem = $reqQty > 0 ? (int) floor($availQty / $reqQty) : 0;
                if ($possibleForThisItem < $maxPossibleBatches) {
                    $maxPossibleBatches = $possibleForThisItem;
                }

                $ingredientBreakdown[] = [
                    'waste_type' => $type,
                    'required_quantity' => $reqQty,
                    'available_quantity' => $availQty,
                    'missing_quantity' => round($missing, 2),
                    'unit' => $item['unit'] ?? 'kg',
                    'coverage_percent' => $coverage,
                    'is_sufficient' => $availQty >= $reqQty,
                ];

                if ($missing > 0) {
                    $missingItems[] = [
                        'waste_type' => $type,
                        'missing_quantity' => round($missing, 2),
                        'unit' => $item['unit'] ?? 'kg',
                    ];
                }

                $totalCoveragePercent += $coverage;
            }

            if ($maxPossibleBatches === PHP_INT_MAX) {
                $maxPossibleBatches = 0;
            }

            $avgCoverage = $ingredientCount > 0 ? (int) round($totalCoveragePercent / $ingredientCount) : 0;

            if ($maxPossibleBatches >= 1) {
                $status = 'ready'; // Ready to Produce
            } elseif ($avgCoverage >= 35 || count($missingItems) < $ingredientCount) {
                $status = 'partial'; // Partially Available
            } else {
                $status = 'insufficient'; // Insufficient Stock
            }

            return [
                'formulation' => $formulation,
                'status' => $status,
                'producible_batches' => $maxPossibleBatches,
                'overall_coverage_percent' => $avgCoverage,
                'ingredient_breakdown' => $ingredientBreakdown,
                'missing_items' => $missingItems,
            ];
        });
    }

    /**
     * Calculate household NPK nutrient stock potential meters based on available inventory.
     */
    public function calculateNutrientPotential(User $user): array
    {
        $stocks = $user->wasteStocks()->get()->keyBy('waste_type');

        $coffeeQty = (float) ($stocks->get('Coffee Grounds')?->quantity ?? 0);
        $bananaQty = (float) ($stocks->get('Banana Peels')?->quantity ?? 0);
        $eggshellQty = (float) ($stocks->get('Eggshells')?->quantity ?? 0);

        // Normalize potential scores from 0 to 100 (capped at 5kg benchmark)
        $nitrogenScore = min(100, (int) round(($coffeeQty / 3.0) * 100));
        $potassiumScore = min(100, (int) round(($bananaQty / 3.0) * 100));
        $calciumScore = min(100, (int) round(($eggshellQty / 2.0) * 100));

        return [
            'nitrogen' => [
                'score' => $nitrogenScore,
                'rating' => $nitrogenScore >= 70 ? 'High' : ($nitrogenScore >= 30 ? 'Moderate' : 'Low'),
                'source_qty' => $coffeeQty,
            ],
            'potassium' => [
                'score' => $potassiumScore,
                'rating' => $potassiumScore >= 70 ? 'High' : ($potassiumScore >= 30 ? 'Moderate' : 'Low'),
                'source_qty' => $bananaQty,
            ],
            'calcium' => [
                'score' => $calciumScore,
                'rating' => $calciumScore >= 70 ? 'High' : ($calciumScore >= 30 ? 'Moderate' : 'Low'),
                'source_qty' => $eggshellQty,
            ],
        ];
    }
}
