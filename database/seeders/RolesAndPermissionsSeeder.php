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
    }
}

