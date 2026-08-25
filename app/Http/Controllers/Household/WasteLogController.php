<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Models\ApprovedFormulation;
use App\Models\WasteLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WasteLogController extends Controller
{
    /** Valid waste types accepted by the system. */
    private const WASTE_TYPES = ['Banana Peels', 'Eggshells', 'Coffee Grounds'];

    /**
     * Display the Home Gardener dashboard.
     *
     * Loads the authenticated user's full waste-log history, ordered newest first.
     */
    public function index(): View
    {
        $wasteLogs = Auth::user()
            ->wasteLogs()
            ->latest('date_recorded')
            ->get();

        return view('household.waste-logs.index', compact('wasteLogs'));
    }

    /**
     * Store a new waste log and return the matched formulation recommendation.
     *
     * Steps:
     *  1. Gate: only users with the `log_waste` permission may proceed.
     *  2. Validate the incoming waste_type against the allowed list.
     *  3. Persist the new WasteLog record for today's date.
     *  4. Look up the approved formulation whose target_waste_type matches.
     *  5. Redirect back carrying the recommendation (or a "not found" notice).
     */
    public function store(Request $request)
    {
        // Spatie permission gate — belt-and-suspenders on top of middleware.
        abort_unless(Auth::user()->can('log_waste'), 403, 'You do not have permission to log waste.');

        $validated = $request->validate([
            'waste_type' => ['required', Rule::in(self::WASTE_TYPES)],
            'quantity'   => ['nullable', 'numeric', 'min:0.01', 'max:9999.99'],
            'unit'       => ['nullable', Rule::in(['kg', 'g', 'items'])],
        ]);

        // Persist the waste log.
        WasteLog::create([
            'user_id'       => Auth::id(),
            'waste_type'    => $validated['waste_type'],
            'quantity'      => $validated['quantity'] ?? null,
            'unit'          => $validated['unit'] ?? 'kg',
            'date_recorded' => now()->toDateString(),
        ]);

        // Fetch the matching approved formulation using AI Service
        $aiService = new \App\Services\AIService();
        $formulation = $aiService->getRecommendation(
            $validated['waste_type'],
            $validated['quantity'] ?? null,
            $validated['unit'] ?? null
        );

        if (! $formulation) {
            return redirect()
                ->route('household.waste-logs.index')
                ->with('info', 'Waste logged! No approved formulation found yet for ' . $validated['waste_type'] . '.');
        }

        // Flash both the recommendation and the submitted waste type so the view
        // can distinguish which card to highlight.
        return redirect()
            ->route('household.waste-logs.index')
            ->with('recommendation', $formulation)
            ->with('logged_waste_type', $validated['waste_type']);
    }
}
