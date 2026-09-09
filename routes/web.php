<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Extension\ExtensionOfficerController;
use App\Http\Controllers\Household\AIAssistantController;
use App\Http\Controllers\Household\FertilizerBatchController;
use App\Http\Controllers\Household\HouseholdExperimentController;
use App\Http\Controllers\Household\RecommendationController;
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
| Authenticated Routes — Role-Based Redirect
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role_type) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Extension Officer' => redirect()->route('officer.dashboard'),
            'Household' => redirect()->route('household.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Household User Routes (Home Gardeners)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'household'])
    ->prefix('household')
    ->name('household.')
    ->group(function () {
        Route::get('/dashboard', [WasteLogController::class, 'index'])->name('dashboard');

        // Waste Logging
        Route::get('/waste-logs', [WasteLogController::class, 'index'])->name('waste-logs.index');
        Route::post('/waste-logs', [WasteLogController::class, 'store'])
            ->middleware('permission:log_waste')
            ->name('waste-logs.store');

        // Deterministic Recommendations
        Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
        Route::post('/recommendations/{formulation}/explain', [RecommendationController::class, 'explain'])->name('recommendations.explain');

        // Fertilizer Batches (Aging / Production)
        Route::get('/batches', [FertilizerBatchController::class, 'index'])->name('batches.index');
        Route::post('/batches', [FertilizerBatchController::class, 'store'])
            ->middleware('permission:log_waste')
            ->name('batches.store');
        Route::patch('/batches/{batch}', [FertilizerBatchController::class, 'updateStatus'])->name('batches.update-status');

        // 4-Week Plant Growth Experiments
        Route::get('/experiments', [HouseholdExperimentController::class, 'index'])->name('experiments.index');
        Route::post('/experiments', [HouseholdExperimentController::class, 'store'])
            ->middleware('permission:track_experiments')
            ->name('experiments.store');
        Route::get('/experiments/{experiment}', [HouseholdExperimentController::class, 'show'])->name('experiments.show');
        Route::post('/experiments/{experiment}/measurements', [HouseholdExperimentController::class, 'storeMeasurement'])
            ->middleware('permission:track_experiments')
            ->name('experiments.measurements.store');
        Route::post('/experiments/{experiment}/ai-summary', [HouseholdExperimentController::class, 'generateAiSummary'])
            ->name('experiments.ai-summary');

        // AI Extension Assistant Chat
        Route::get('/chat', [AIAssistantController::class, 'chatHistory'])->name('chat.history');
        Route::post('/chat', [AIAssistantController::class, 'sendMessage'])->name('chat.send');
    });

/*
|--------------------------------------------------------------------------
| Extension Officer Workspace Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'officer'])
    ->prefix('officer')
    ->name('officer.')
    ->group(function () {
        Route::get('/dashboard', [ExtensionOfficerController::class, 'dashboard'])->name('dashboard');

        // Formulations Management
        Route::get('/formulations', [ExtensionOfficerController::class, 'formulationsIndex'])->name('formulations.index');
        Route::post('/formulations', [ExtensionOfficerController::class, 'formulationsStore'])
            ->middleware('permission:manage_formulations')
            ->name('formulations.store');
        Route::delete('/formulations/{formulation}', [ExtensionOfficerController::class, 'formulationsDestroy'])
            ->middleware('permission:manage_formulations')
            ->name('formulations.destroy');

        // Cooperative Plant Trials Review
        Route::get('/experiments', [ExtensionOfficerController::class, 'experimentsIndex'])->name('experiments.index');
    });

/*
|--------------------------------------------------------------------------
| System Administrator Routes (User & Platform Access Management Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function (\App\Services\AdminAnalyticsService $analyticsService) {
            $userCount = \App\Models\User::count();
            $householdCount = \App\Models\User::where('role_type', 'Household')->count();
            $officerCount = \App\Models\User::where('role_type', 'Extension Officer')->count();
            $adminCount = \App\Models\User::where('role_type', 'Admin')->count();

            $wasteAnalytics = $analyticsService->getWasteVolumeByType();
            $growthAnalytics = $analyticsService->getGrowthTrendComparison();
            $batchAnalytics = $analyticsService->getBatchStatusDistribution();

            return view('admin.dashboard', compact(
                'userCount',
                'householdCount',
                'officerCount',
                'adminCount',
                'wasteAnalytics',
                'growthAnalytics',
                'batchAnalytics'
            ));
        })->name('dashboard');

        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

require __DIR__.'/auth.php';
