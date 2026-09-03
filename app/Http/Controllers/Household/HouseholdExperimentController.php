<?php

namespace App\Http\Controllers\Household;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\GrowthMeasurement;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HouseholdExperimentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $experiments = $user->experiments()
            ->with('growthMeasurements')
            ->latest('start_date')
            ->get();

        return view('household.experiments.index', compact('experiments'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('track_experiments'), 403);

        $validated = $request->validate([
            'plant_species' => ['required', 'string', 'max:100'],
            'fertilizer_type' => ['required', 'in:Organic,Commercial Control'],
            'start_date' => ['required', 'date'],
        ]);

        $experiment = Experiment::create([
            'user_id' => Auth::id(),
            'plant_species' => $validated['plant_species'],
            'fertilizer_type' => $validated['fertilizer_type'],
            'start_date' => $validated['start_date'],
        ]);

        return redirect()->route('household.experiments.show', $experiment)
            ->with('info', "Plant Growth Trial for {$experiment->plant_species} ({$experiment->fertilizer_type}) initialized.");
    }

    public function show(Experiment $experiment, AIService $aiService)
    {
        abort_unless($experiment->user_id === Auth::id() || Auth::user()->hasAdminAccess(), 403);

        $measurements = $experiment->growthMeasurements()
            ->orderBy('week_number', 'asc')
            ->get();

        // Find companion trial of opposite fertilizer group for comparative analysis
        $companionTrial = Experiment::where('user_id', $experiment->user_id)
            ->where('plant_species', $experiment->plant_species)
            ->where('experiment_id', '!=', $experiment->experiment_id)
            ->with('growthMeasurements')
            ->first();

        return view('household.experiments.show', compact('experiment', 'measurements', 'companionTrial'));
    }

    public function storeMeasurement(Request $request, Experiment $experiment)
    {
        abort_unless($experiment->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'week_number' => ['required', 'integer', 'between:1,4'],
            'plant_height_cm' => ['required', 'numeric', 'min:0.1', 'max:500.00'],
            'soil_pH' => ['required', 'numeric', 'between:1.0,14.0'],
            'leaf_vitality' => ['required', 'string', 'max:50'],
        ]);

        GrowthMeasurement::updateOrCreate(
            [
                'experiment_id' => $experiment->experiment_id,
                'week_number' => $validated['week_number'],
            ],
            [
                'plant_height_cm' => $validated['plant_height_cm'],
                'soil_pH' => $validated['soil_pH'],
                'leaf_vitality' => $validated['leaf_vitality'],
            ]
        );

        return redirect()->route('household.experiments.show', $experiment)
            ->with('info', "Week {$validated['week_number']} growth measurement recorded successfully.");
    }

    public function generateAiSummary(Experiment $experiment, AIService $aiService)
    {
        abort_unless($experiment->user_id === Auth::id() || Auth::user()->hasAdminAccess(), 403);

        $summary = $aiService->summarizeGrowthExperiment($experiment);

        return redirect()->route('household.experiments.show', $experiment)
            ->with('ai_summary', $summary);
    }
}
