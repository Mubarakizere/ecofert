<?php

namespace Database\Seeders;

use App\Models\User;
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
        // ──────────────────────────────────────────────
        // 1. Clear cached roles and permissions
        // ──────────────────────────────────────────────
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ──────────────────────────────────────────────
        // 2. Create Permissions
        // ──────────────────────────────────────────────
        $permissions = [
            'manage_formulations',  // Create, read, update, delete approved formulas
            'log_waste',            // Record daily food waste
            'track_experiments',    // Record weekly plant growth data
            'view_reports',         // View aggregate system data
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        // ──────────────────────────────────────────────
        // 3. Create Roles and assign Permissions
        // ──────────────────────────────────────────────
        $admin = Role::findOrCreate('Admin', 'web');
        $admin->syncPermissions(['manage_formulations', 'view_reports', 'track_experiments']);

        $extensionOfficer = Role::findOrCreate('Extension Officer', 'web');
        $extensionOfficer->syncPermissions(['manage_formulations', 'view_reports', 'track_experiments']);

        $household = Role::findOrCreate('Household', 'web');
        $household->syncPermissions(['log_waste']);

        // ──────────────────────────────────────────────
        // 4. Create Test Users and assign Roles
        // ──────────────────────────────────────────────
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
    }
}
