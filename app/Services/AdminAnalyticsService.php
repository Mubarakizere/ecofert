<?php

namespace App\Services;

use App\Models\Experiment;
use App\Models\FertilizerBatch;
use App\Models\GrowthMeasurement;
use App\Models\WasteLog;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsService
{
    /**
     * Compute cooperative food-waste collection totals by waste material type.
     */
    public function getWasteVolumeByType(): array
    {
        $bananaKg = (float) WasteLog::where('waste_type', 'Banana Peels')
            ->where('transaction_type', 'added')
            ->sum('quantity');

        $eggshellKg = (float) WasteLog::where('waste_type', 'Eggshells')
            ->where('transaction_type', 'added')
            ->sum('quantity');

        $coffeeKg = (float) WasteLog::where('waste_type', 'Coffee Grounds')
            ->where('transaction_type', 'added')
            ->sum('quantity');

        return [
            'labels' => ['Banana Peels (K)', 'Eggshells (Ca)', 'Coffee Grounds (N)'],
            'data' => [round($bananaKg, 2), round($eggshellKg, 2), round($coffeeKg, 2)],
            'totals' => [
                'banana' => round($bananaKg, 2),
                'eggshell' => round($eggshellKg, 2),
                'coffee' => round($coffeeKg, 2),
                'aggregate' => round($bananaKg + $eggshellKg + $coffeeKg, 2),
            ],
        ];
    }

    /**
     * Compute average 4-week plant height growth progression for Organic vs Commercial Control groups.
     */
    public function getGrowthTrendComparison(): array
    {
        $weeks = [1, 2, 3, 4];
        $organicHeights = [];
        $commercialHeights = [];

        foreach ($weeks as $w) {
            $organicAvg = (float) GrowthMeasurement::whereHas('experiment', function ($q) {
                $q->where('fertilizer_type', 'Organic');
            })->where('week_number', $w)->avg('plant_height_cm');

            $commercialAvg = (float) GrowthMeasurement::whereHas('experiment', function ($q) {
                $q->where('fertilizer_type', 'Commercial Control');
            })->where('week_number', $w)->avg('plant_height_cm');

            $organicHeights[] = round($organicAvg ?: 0, 2);
            $commercialHeights[] = round($commercialAvg ?: 0, 2);
        }

        return [
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            'organic' => $organicHeights,
            'commercial' => $commercialHeights,
        ];
    }

    /**
     * Compute distribution of fertilizer production batches by status.
     */
    public function getBatchStatusDistribution(): array
    {
        $aging = FertilizerBatch::where('status', 'aging')->count();
        $ready = FertilizerBatch::where('status', 'ready')->count();
        $applied = FertilizerBatch::where('status', 'applied')->count();

        return [
            'labels' => ['Aging / Fermenting', 'Ready for Use', 'Applied to Crops'],
            'data' => [$aging, $ready, $applied],
            'aging' => $aging,
            'ready' => $ready,
            'applied' => $applied,
            'total' => $aging + $ready + $applied,
        ];
    }
}
