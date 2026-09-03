<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Models\ApprovedFormulation;
use App\Models\FertilizerBatch;
use App\Models\UserWasteStock;
use App\Models\WasteLog;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FertilizerBatchController extends Controller
{
    public function index(RecommendationService $recommendationService)
    {
        $user = Auth::user();
        $batches = $user->fertilizerBatches()
            ->with('formulation')
            ->latest('start_date')
            ->get();

        $recommendations = $recommendationService->evaluateStockForUser($user);

        return view('household.batches.index', compact('batches', 'recommendations'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('log_waste'), 403);

        $validated = $request->validate([
            'formulation_id' => ['required', 'exists:approved_formulations,formula_id'],
            'batch_count' => ['nullable', 'integer', 'min:1', 'max:10'],
        ]);

        $user = Auth::user();
        $formulation = ApprovedFormulation::findOrFail($validated['formulation_id']);
        $batchCount = (int) ($validated['batch_count'] ?? 1);

        $required = $formulation->required_ingredients ?? [];
        if (empty($required)) {
            $required = [['waste_type' => $formulation->target_waste_type, 'quantity' => 1.0, 'unit' => 'kg']];
        }

        // Verify stock sufficiency for requested batch count
        foreach ($required as $item) {
            $type = $item['waste_type'];
            $reqQty = ((float) ($item['quantity'] ?? 1.0)) * $batchCount;

            $stock = UserWasteStock::where('user_id', $user->id)
                ->where('waste_type', $type)
                ->first();

            if (! $stock || (float) $stock->quantity < $reqQty) {
                return redirect()->back()->with('error', "Insufficient {$type} in stock. Required: {$reqQty}kg.");
            }
        }

        // Perform stock deductions and create batch
        $usedIngredients = [];
        foreach ($required as $item) {
            $type = $item['waste_type'];
            $reqQty = ((float) ($item['quantity'] ?? 1.0)) * $batchCount;

            $stock = UserWasteStock::where('user_id', $user->id)
                ->where('waste_type', $type)
                ->first();

            $stock->decrement('quantity', $reqQty);

            WasteLog::create([
                'user_id' => $user->id,
                'waste_type' => $type,
                'quantity' => $reqQty,
                'unit' => 'kg',
                'date_recorded' => now()->toDateString(),
                'transaction_type' => 'used',
            ]);

            $usedIngredients[] = [
                'waste_type' => $type,
                'quantity' => $reqQty,
                'unit' => 'kg',
            ];
        }

        $fermentationDays = $formulation->fermentation_days ?? 7;
        $startDate = now();
        $readyDate = now()->addDays($fermentationDays);
        $batchCode = 'BATCH-' . strtoupper(Str::random(6));

        $batch = FertilizerBatch::create([
            'batch_code' => $batchCode,
            'user_id' => $user->id,
            'formulation_id' => $formulation->formula_id,
            'status' => 'aging',
            'used_ingredients' => $usedIngredients,
            'start_date' => $startDate->toDateString(),
            'estimated_ready_date' => $readyDate->toDateString(),
            'notes' => "Production batch of {$formulation->title} ({$batchCount} batch multiplier).",
        ]);

        return redirect()->route('household.batches.index')
            ->with('info', "Production batch {$batchCode} started! Stock deducted. Fermentation countdown active ({$fermentationDays} days).");
    }

    public function updateStatus(Request $request, FertilizerBatch $batch)
    {
        abort_unless($batch->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:aging,ready,applied,discarded'],
        ]);

        $batch->update(['status' => $validated['status']]);

        return redirect()->back()->with('info', "Batch {$batch->batch_code} status updated to " . ucfirst($validated['status']) . ".");
    }
}
