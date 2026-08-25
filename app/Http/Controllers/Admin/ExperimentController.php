<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\GrowthMeasurement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExperimentController extends Controller
{
    /**
     * Display a list of the user's active 4-week plant experiments.
     */
    public function index(): View
    {
        $experiments = Auth::user()
            ->experiments()
            ->with('growthMeasurements')
            ->latest('start_date')
            ->latest('created_at')
            ->get();

        return view('admin.experiments.index', compact('experiments'));
    }

    /**
     * Start a new 4-week plant growth trial.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::user()->can('track_experiments'), 403, 'Permission denied.');

        $validated = $request->validate([
            'fertilizer_type' => ['required', Rule::in(['Organic', 'Commercial Control'])],
            'plant_species'   => ['required', 'string', 'max:100'],
            'start_date'      => ['required', 'date'],
        ]);

        $experiment = Experiment::create([
            'user_id'         => Auth::id(),
            'fertilizer_type' => $validated['fertilizer_type'],
            'plant_species'   => $validated['plant_species'],
            'start_date'      => $validated['start_date'],
        ]);

        return redirect()
            ->route('admin.experiments.show', $experiment->experiment_id)
            ->with('success', 'New 4-week experiment created successfully! You can now log your weekly growth measurements.');
    }

    /**
     * Display a specific experiment's timeline and measurement log form.
     */
    public function show(Experiment $experiment): View
    {
        // Authorize user ownership
        if ($experiment->user_id && $experiment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this experiment.');
        }

        $experiment->load(['growthMeasurements' => function ($query) {
            $query->orderBy('week_number', 'asc');
        }]);

        return view('admin.experiments.show', compact('experiment'));
    }

    /**
     * Save a new weekly growth measurement reading for the experiment.
     */
    public function storeMeasurement(Request $request, Experiment $experiment): RedirectResponse
    {
        abort_unless(Auth::user()->can('track_experiments'), 403, 'Permission denied.');

        // Authorize user ownership
        if ($experiment->user_id && $experiment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this experiment.');
        }

        $validated = $request->validate([
            'week_number'     => ['required', 'integer', 'between:1,4'],
            'plant_height_cm' => ['required', 'numeric', 'min:0', 'max:500'],
            'soil_pH'         => ['required', 'numeric', 'between:0,14'],
            'leaf_vitality'   => ['required', 'string', 'max:50', Rule::in(['Poor', 'Fair', 'Good', 'Excellent', 'Vibrant'])],
        ]);

        // Prevent duplicate week measurement for the same experiment
        GrowthMeasurement::updateOrCreate(
            [
                'experiment_id' => $experiment->experiment_id,
                'week_number'   => $validated['week_number'],
            ],
            [
                'plant_height_cm' => $validated['plant_height_cm'],
                'soil_pH'         => $validated['soil_pH'],
                'leaf_vitality'   => $validated['leaf_vitality'],
            ]
        );

        return redirect()
            ->route('admin.experiments.show', $experiment->experiment_id)
            ->with('success', 'Week ' . $validated['week_number'] . ' growth measurement recorded successfully!');
    }
}
