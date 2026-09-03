<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Models\UserWasteStock;
use App\Models\WasteLog;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WasteLogController extends Controller
{
    private const WASTE_TYPES = ['Banana Peels', 'Eggshells', 'Coffee Grounds'];

    public function index(RecommendationService $recommendationService): View
    {
        $user = Auth::user();
        $wasteLogs = $user->wasteLogs()
            ->latest('date_recorded')
            ->latest('created_at')
            ->get();

        $wasteStocks = $user->wasteStocks()->get()->keyBy('waste_type');
        $nutrientMeters = $recommendationService->calculateNutrientPotential($user);
        $recommendations = $recommendationService->evaluateStockForUser($user);
        $activeBatches = $user->fertilizerBatches()->with('formulation')->whereIn('status', ['aging', 'ready'])->get();

        return view('household.dashboard', compact('wasteLogs', 'wasteStocks', 'nutrientMeters', 'recommendations', 'activeBatches'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('log_waste'), 403, 'You do not have permission to log waste.');

        $validated = $request->validate([
            'waste_type' => ['required', Rule::in(self::WASTE_TYPES)],
            'quantity'   => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'unit'       => ['nullable', Rule::in(['kg', 'g', 'items'])],
        ]);

        $quantity = (float) $validated['quantity'];
        if (isset($validated['unit']) && $validated['unit'] === 'g') {
            $quantity = $quantity / 1000;
        }

        WasteLog::create([
            'user_id'          => Auth::id(),
            'waste_type'       => $validated['waste_type'],
            'quantity'         => $quantity,
            'unit'             => 'kg',
            'date_recorded'    => now()->toDateString(),
            'transaction_type' => 'added',
        ]);

        $stock = UserWasteStock::firstOrCreate(
            ['user_id' => Auth::id(), 'waste_type' => $validated['waste_type']],
            ['quantity' => 0, 'unit' => 'kg']
        );
        $stock->increment('quantity', $quantity);

        return redirect()
            ->route('household.dashboard')
            ->with('info', "Successfully logged {$quantity}kg of {$validated['waste_type']}. Stock updated.");
    }
}
