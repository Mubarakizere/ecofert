<?php

namespace Database\Seeders;

use App\Models\FertilizerBatch;
use App\Models\ApprovedFormulation;
use App\Models\User;
use App\Models\UserWasteStock;
use App\Models\WasteLog;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed demo waste logs, waste stocks, and fertilizer batches
     * for the Household test user so the dashboard displays real data.
     */
    public function run(): void
    {
        $gardener = User::where('email', 'gardener@ecofert.com')->first();

        if (! $gardener) {
            $this->command->warn('Household user (gardener@ecofert.com) not found. Skipping demo data.');
            return;
        }

        // ── 1. Waste Logs ────────────────────────────────────────────────
        $wasteLogs = [
            [
                'user_id'          => $gardener->id,
                'waste_type'       => 'Banana Peels',
                'quantity'         => 2.50,
                'unit'             => 'kg',
                'date_recorded'    => now()->subDays(7)->toDateString(),
                'transaction_type' => 'added',
            ],
            [
                'user_id'          => $gardener->id,
                'waste_type'       => 'Eggshells',
                'quantity'         => 1.20,
                'unit'             => 'kg',
                'date_recorded'    => now()->subDays(6)->toDateString(),
                'transaction_type' => 'added',
            ],
            [
                'user_id'          => $gardener->id,
                'waste_type'       => 'Coffee Grounds',
                'quantity'         => 1.80,
                'unit'             => 'kg',
                'date_recorded'    => now()->subDays(5)->toDateString(),
                'transaction_type' => 'added',
            ],
            [
                'user_id'          => $gardener->id,
                'waste_type'       => 'Banana Peels',
                'quantity'         => 1.00,
                'unit'             => 'kg',
                'date_recorded'    => now()->subDays(4)->toDateString(),
                'transaction_type' => 'used',
            ],
            [
                'user_id'          => $gardener->id,
                'waste_type'       => 'Eggshells',
                'quantity'         => 0.50,
                'unit'             => 'kg',
                'date_recorded'    => now()->subDays(4)->toDateString(),
                'transaction_type' => 'used',
            ],
        ];

        foreach ($wasteLogs as $log) {
            WasteLog::create($log);
        }

        // ── 2. User Waste Stocks (net after deposits & batch usage) ──────
        $stocks = [
            ['waste_type' => 'Banana Peels',   'quantity' => 1.50, 'unit' => 'kg'],
            ['waste_type' => 'Eggshells',      'quantity' => 0.70, 'unit' => 'kg'],
            ['waste_type' => 'Coffee Grounds', 'quantity' => 1.80, 'unit' => 'kg'],
        ];

        foreach ($stocks as $stock) {
            UserWasteStock::create(array_merge($stock, ['user_id' => $gardener->id]));
        }

        // ── 3. Fertilizer Batches ────────────────────────────────────────
        $potassiumBrew = ApprovedFormulation::where('title', 'Potassium & Mineral Liquid Brew')->first();

        if ($potassiumBrew) {
            FertilizerBatch::create([
                'batch_code'          => 'BATCH-ECO2026-01',
                'user_id'             => $gardener->id,
                'formulation_id'      => $potassiumBrew->formula_id,
                'status'              => 'aging',
                'used_ingredients'    => [
                    ['waste_type' => 'Banana Peels', 'quantity' => 1.0, 'unit' => 'kg'],
                    ['waste_type' => 'Eggshells',    'quantity' => 0.5, 'unit' => 'kg'],
                ],
                'start_date'          => now()->subDays(8)->toDateString(),
                'estimated_ready_date' => now()->subDay()->toDateString(),
                'notes'               => 'First production batch – Potassium-rich liquid brew for tomatoes.',
            ]);

            FertilizerBatch::create([
                'batch_code'          => 'BATCH-' . strtoupper(substr(uniqid(), -6)),
                'user_id'             => $gardener->id,
                'formulation_id'      => $potassiumBrew->formula_id,
                'status'              => 'applied',
                'used_ingredients'    => [
                    ['waste_type' => 'Banana Peels', 'quantity' => 1.0, 'unit' => 'kg'],
                    ['waste_type' => 'Eggshells',    'quantity' => 0.5, 'unit' => 'kg'],
                ],
                'start_date'          => now()->subDays(20)->toDateString(),
                'estimated_ready_date' => now()->subDays(13)->toDateString(),
                'notes'               => 'Completed and applied to garden bed.',
            ]);
        }

        $this->command->info('Demo waste logs, stocks, and fertilizer batches seeded for gardener@ecofert.com.');
    }
}
