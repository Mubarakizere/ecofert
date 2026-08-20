<?php

use App\Http\Controllers\Admin\AdminFormulationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (any role) — role-based redirect
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role_type) {
            'Admin', 'Extension Officer' => redirect()->route('admin.dashboard'),
            'Household' => redirect()->route('household.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Profile Routes (any authenticated user)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Household Routes — waste logging, formulation viewing
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'household'])
    ->prefix('household')
    ->name('household.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('household.dashboard');
        })->name('dashboard');

        // Waste logging routes (to be connected to controllers later)
        // Route::resource('waste-logs', WasteLogController::class);

        // View approved formulations
        // Route::get('/formulations', [FormulationController::class, 'index'])->name('formulations.index');
        // Route::get('/formulations/{formulation}', [FormulationController::class, 'show'])->name('formulations.show');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes — formulations management, experiments, measurements
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Formulation management (CRUD)
        Route::resource('formulations', AdminFormulationController::class)
            ->parameters(['formulations' => 'formulation'])
            ->except(['show']);

        // Experiment & growth measurement routes
        // Route::resource('experiments', Admin\ExperimentController::class);
        // Route::resource('experiments.measurements', Admin\GrowthMeasurementController::class)->shallow();
    });

require __DIR__.'/auth.php';
