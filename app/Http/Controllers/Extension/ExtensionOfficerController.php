<?php

namespace App\Http\Controllers\Extension;

use App\Http\Controllers\Controller;
use App\Models\ApprovedFormulation;
use App\Models\Experiment;
use App\Models\User;
use App\Services\AdminAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ExtensionOfficerController extends Controller
{
    public function dashboard(AdminAnalyticsService $analyticsService)
    {
        $formulationsCount = ApprovedFormulation::count();
        $householdCount = User::where('role_type', 'Household')->count();
        $experimentsCount = Experiment::count();

        $recentFormulations = ApprovedFormulation::latest()->take(5)->get();
        $recentExperiments = Experiment::with('user', 'growthMeasurements')->latest()->take(5)->get();

        $wasteAnalytics = $analyticsService->getWasteVolumeByType();
        $growthAnalytics = $analyticsService->getGrowthTrendComparison();
        $batchAnalytics = $analyticsService->getBatchStatusDistribution();

        return view('officer.dashboard', compact(
            'formulationsCount',
            'householdCount',
            'experimentsCount',
            'recentFormulations',
            'recentExperiments',
            'wasteAnalytics',
            'growthAnalytics',
            'batchAnalytics'
        ));
    }

    public function formulationsIndex()
    {
        $formulations = ApprovedFormulation::latest()->get();
        return view('officer.formulations.index', compact('formulations'));
    }

    public function formulationsStore(Request $request)
    {
        abort_unless(Auth::user()->can('manage_formulations'), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'target_waste_type' => ['required', Rule::in(['Banana Peels', 'Eggshells', 'Coffee Grounds'])],
            'npk_ratio' => ['required', 'string', 'max:50'],
            'primary_nutrients' => ['required', 'string', 'max:150'],
            'fermentation_days' => ['required', 'integer', 'min:1', 'max:90'],
            'yield_quantity' => ['required', 'numeric', 'min:0.1'],
            'yield_unit' => ['required', 'string', 'max:20'],
            'preparation_steps' => ['required', 'string'],
            'application_guidance' => ['required', 'string'],
            'required_ingredients' => ['required', 'array', 'min:1'],
            'required_ingredients.*.waste_type' => ['required', Rule::in(['Banana Peels', 'Eggshells', 'Coffee Grounds'])],
            'required_ingredients.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'required_ingredients.*.unit' => ['required', 'string', 'max:10'],
        ]);

        ApprovedFormulation::create($validated);

        return redirect()->route('officer.formulations.index')
            ->with('info', "Organic formulation '{$validated['title']}' created and validated successfully.");
    }

    public function formulationsDestroy(ApprovedFormulation $formulation)
    {
        abort_unless(Auth::user()->can('manage_formulations'), 403);

        $title = $formulation->title;
        $formulation->delete();

        return redirect()->route('officer.formulations.index')
            ->with('info', "Formulation '{$title}' removed.");
    }

    public function experimentsIndex()
    {
        $experiments = Experiment::with('user', 'growthMeasurements')
            ->latest('start_date')
            ->get();

        return view('officer.experiments.index', compact('experiments'));
    }
}
