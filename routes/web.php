<?php

use App\Http\Controllers\Admin\AdminFormulationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Household\WasteLogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
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
        Route::get('/dashboard', [WasteLogController::class, 'index'])->name('dashboard');

        // ── Waste Logging & Recommendation Engine (Day 4) ────────────────
        // GET  /household/waste-logs         → show dashboard + history
        // POST /household/waste-logs         → submit waste & get recommendation
        // The store route additionally requires the 'log_waste' Spatie permission.
        Route::get('/waste-logs', [WasteLogController::class, 'index'])->name('waste-logs.index');
        Route::post('/waste-logs', [WasteLogController::class, 'store'])
            ->middleware('permission:log_waste')
            ->name('waste-logs.store');


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
            $formulationsCount = \App\Models\ApprovedFormulation::count();
            $householdCount = \App\Models\User::where('role_type', 'Household')->count();
            $experimentsCount = \App\Models\Experiment::count();
            $recentFormulations = \App\Models\ApprovedFormulation::latest('updated_at')->take(5)->get();

            return view('admin.dashboard', compact(
                'formulationsCount',
                'householdCount',
                'experimentsCount',
                'recentFormulations'
            ));
        })->name('dashboard');

        // Formulation management (CRUD)
        Route::resource('formulations', AdminFormulationController::class)
            ->parameters(['formulations' => 'formulation'])
            ->except(['show']);

        // User management (Admin only)
        Route::resource('users', UserController::class)
            ->only(['index', 'create', 'store']);

        // Experiment & growth measurement routes
        Route::get('/experiments', [\App\Http\Controllers\Admin\ExperimentController::class, 'index'])->name('experiments.index');
        Route::post('/experiments', [\App\Http\Controllers\Admin\ExperimentController::class, 'store'])
            ->middleware('permission:track_experiments')
            ->name('experiments.store');
        Route::get('/experiments/{experiment}', [\App\Http\Controllers\Admin\ExperimentController::class, 'show'])->name('experiments.show');
        Route::post('/experiments/{experiment}/measurements', [\App\Http\Controllers\Admin\ExperimentController::class, 'storeMeasurement'])
            ->middleware('permission:track_experiments')
            ->name('experiments.measurements.store');
    });

require __DIR__.'/auth.php';
