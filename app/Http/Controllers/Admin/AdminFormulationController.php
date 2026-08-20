<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovedFormulation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminFormulationController extends Controller
{
    /**
     * Display a listing of all approved formulations.
     */
    public function index(): View
    {
        $formulations = ApprovedFormulation::latest()->get();

        return view('admin.formulations.index', compact('formulations'));
    }

    /**
     * Show the form for creating a new formulation.
     */
    public function create(): View
    {
        return view('admin.formulations.create');
    }

    /**
     * Store a newly created formulation in the database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'target_waste_type' => ['required', Rule::in(['Banana Peels', 'Eggshells', 'Coffee Grounds'])],
            'preparation_steps' => ['required', 'string', 'min:10'],
            'application_guidance' => ['required', 'string', 'min:10'],
        ]);

        ApprovedFormulation::create($validated);

        return redirect()
            ->route('admin.formulations.index')
            ->with('success', 'Formulation created successfully.');
    }

    /**
     * Show the form for editing the specified formulation.
     */
    public function edit(ApprovedFormulation $formulation): View
    {
        return view('admin.formulations.edit', compact('formulation'));
    }

    /**
     * Update the specified formulation in the database.
     */
    public function update(Request $request, ApprovedFormulation $formulation): RedirectResponse
    {
        $validated = $request->validate([
            'target_waste_type' => ['required', Rule::in(['Banana Peels', 'Eggshells', 'Coffee Grounds'])],
            'preparation_steps' => ['required', 'string', 'min:10'],
            'application_guidance' => ['required', 'string', 'min:10'],
        ]);

        $formulation->update($validated);

        return redirect()
            ->route('admin.formulations.index')
            ->with('success', 'Formulation updated successfully.');
    }

    /**
     * Remove the specified formulation from the database.
     */
    public function destroy(ApprovedFormulation $formulation): RedirectResponse
    {
        $formulation->delete();

        return redirect()
            ->route('admin.formulations.index')
            ->with('success', 'Formulation deleted successfully.');
    }
}
