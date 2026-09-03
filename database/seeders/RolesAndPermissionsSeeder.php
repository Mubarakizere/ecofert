<?php

namespace Database\Seeders;

use App\Models\ApprovedFormulation;
use App\Models\Experiment;
use App\Models\FertilizerBatch;
use App\Models\GrowthMeasurement;
use App\Models\User;
use App\Models\UserWasteStock;
use App\Models\WasteLog;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed roles, permissions, and test users for the EcoFert application.
     */
    public function run(): void
    {
        // 1. Clear cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Create Permissions
        $permissionNames = [
            'manage_formulations',
            'log_waste',
            'track_experiments',
            'view_reports',
        ];

        $permissions = [];
        foreach ($permissionNames as $name) {
            $permissions[$name] = Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 3. Create Roles and assign Permissions
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            $permissions['manage_formulations'],
            $permissions['view_reports'],
            $permissions['track_experiments'],
        ]);

        $extensionOfficer = Role::firstOrCreate(['name' => 'Extension Officer', 'guard_name' => 'web']);
        $extensionOfficer->syncPermissions([
            $permissions['manage_formulations'],
            $permissions['view_reports'],
            $permissions['track_experiments'],
        ]);

        $household = Role::firstOrCreate(['name' => 'Household', 'guard_name' => 'web']);
        $household->syncPermissions([
            $permissions['log_waste'],
            $permissions['track_experiments'],
        ]);

        // 4. Create Test Users and assign Roles
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@ecofert.com'],
            [
                'name'              => 'System Administrator',
                'role_type'         => 'Admin',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->syncRoles($admin);

        $gardener = User::updateOrCreate(
            ['email' => 'gardener@ecofert.com'],
            [
                'name'              => 'Home Gardener',
                'role_type'         => 'Household',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $gardener->syncRoles($household);

        $officer = User::updateOrCreate(
            ['email' => 'officer@ecofert.com'],
            [
                'name'              => 'Extension Officer',
                'role_type'         => 'Extension Officer',
                'password'          => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $officer->syncRoles($extensionOfficer);

        // 5. Seed Household Stock Inventory
        UserWasteStock::updateOrCreate(
            ['user_id' => $gardener->id, 'waste_type' => 'Banana Peels'],
            ['quantity' => 2.50, 'unit' => 'kg']
        );
        UserWasteStock::updateOrCreate(
            ['user_id' => $gardener->id, 'waste_type' => 'Eggshells'],
            ['quantity' => 1.20, 'unit' => 'kg']
        );
        UserWasteStock::updateOrCreate(
            ['user_id' => $gardener->id, 'waste_type' => 'Coffee Grounds'],
            ['quantity' => 1.80, 'unit' => 'kg']
        );

        // 6. Seed Household Waste Logs
        WasteLog::create([
            'user_id' => $gardener->id,
            'waste_type' => 'Banana Peels',
            'quantity' => 2.50,
            'unit' => 'kg',
            'date_recorded' => now()->subDays(3)->toDateString(),
            'transaction_type' => 'added',
        ]);
        WasteLog::create([
            'user_id' => $gardener->id,
            'waste_type' => 'Eggshells',
            'quantity' => 1.20,
            'unit' => 'kg',
            'date_recorded' => now()->subDays(2)->toDateString(),
            'transaction_type' => 'added',
        ]);
        WasteLog::create([
            'user_id' => $gardener->id,
            'waste_type' => 'Coffee Grounds',
            'quantity' => 1.80,
            'unit' => 'kg',
            'date_recorded' => now()->subDay()->toDateString(),
            'transaction_type' => 'added',
        ]);

        // 7. Seed Active Fertilizer Production Batch
        $formula = ApprovedFormulation::first();
        if ($formula) {
            FertilizerBatch::updateOrCreate(
                ['batch_code' => 'BATCH-ECO2026-01'],
                [
                    'user_id' => $gardener->id,
                    'formulation_id' => $formula->formula_id,
                    'status' => 'aging',
                    'used_ingredients' => [
                        ['waste_type' => 'Banana Peels', 'quantity' => 1.0, 'unit' => 'kg'],
                        ['waste_type' => 'Eggshells', 'quantity' => 0.5, 'unit' => 'kg'],
                    ],
                    'start_date' => now()->subDays(4)->toDateString(),
                    'estimated_ready_date' => now()->addDays(3)->toDateString(),
                    'notes' => 'Potassium & Mineral Liquid Brew aging in covered aerated container.',
                ]
            );
        }

        // 8. Seed 4-Week Plant Growth Experiments
        $expOrganic = Experiment::updateOrCreate(
            ['user_id' => $gardener->id, 'plant_species' => 'Red Radish', 'fertilizer_type' => 'Organic'],
            ['start_date' => now()->subDays(28)->toDateString()]
        );

        $expCommercial = Experiment::updateOrCreate(
            ['user_id' => $gardener->id, 'plant_species' => 'Red Radish', 'fertilizer_type' => 'Commercial Control'],
            ['start_date' => now()->subDays(28)->toDateString()]
        );

        // Organic Measurements
        $orgData = [
            ['week' => 1, 'height' => 3.2, 'ph' => 6.4, 'vitality' => 'Healthy Green'],
            ['week' => 2, 'height' => 7.8, 'ph' => 6.5, 'vitality' => 'Healthy Green'],
            ['week' => 3, 'height' => 13.5, 'ph' => 6.6, 'vitality' => 'Deep Green'],
            ['week' => 4, 'height' => 19.2, 'ph' => 6.7, 'vitality' => 'Deep Green'],
        ];
        foreach ($orgData as $d) {
            GrowthMeasurement::updateOrCreate(
                ['experiment_id' => $expOrganic->experiment_id, 'week_number' => $d['week']],
                ['plant_height_cm' => $d['height'], 'soil_pH' => $d['ph'], 'leaf_vitality' => $d['vitality']]
            );
        }

        // Commercial Measurements
        $comData = [
            ['week' => 1, 'height' => 3.0, 'ph' => 6.2, 'vitality' => 'Healthy Green'],
            ['week' => 2, 'height' => 6.9, 'ph' => 6.1, 'vitality' => 'Healthy Green'],
            ['week' => 3, 'height' => 11.4, 'ph' => 6.0, 'vitality' => 'Moderate Yellowing'],
            ['week' => 4, 'height' => 16.1, 'ph' => 5.9, 'vitality' => 'Moderate Yellowing'],
        ];
        foreach ($comData as $d) {
            GrowthMeasurement::updateOrCreate(
                ['experiment_id' => $expCommercial->experiment_id, 'week_number' => $d['week']],
                ['plant_height_cm' => $d['height'], 'soil_pH' => $d['ph'], 'leaf_vitality' => $d['vitality']]
            );
        }
    }
}
