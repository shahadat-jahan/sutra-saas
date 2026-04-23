<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * The 7 permission modules for the Sutra SaaS platform.
     *
     * Each module maps to a set of CRUD-style permission verbs.
     */
    private const MODULES = [
        // ─── Admin-Level Modules ───────────────────────────────────
        'shops'           => ['view', 'create', 'edit', 'delete'],
        'users'           => ['view', 'create', 'edit', 'delete'],
        'settings'        => ['view', 'edit'],

        // ─── Tenant-Level Modules ──────────────────────────────────
        'products'        => ['view', 'create', 'edit', 'delete'],
        'inventory'       => ['view', 'create', 'edit', 'delete'],
        'transactions'    => ['view', 'create', 'edit', 'delete'],
        'daily-summaries' => ['view', 'create'],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ─── 1. Create all permissions ─────────────────────────────
        $allPermissions = [];

        foreach (self::MODULES as $module => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$action} {$module}";
                Permission::firstOrCreate(['name' => $permissionName]);
                $allPermissions[] = $permissionName;
            }
        }

        // ─── 2. Super-Admin — gets EVERYTHING ──────────────────────
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions($allPermissions);

        // ─── 3. Shop-Owner — tenant-level management ───────────────
        $shopOwner = Role::firstOrCreate(['name' => 'shop-owner']);
        $shopOwner->syncPermissions([
            // Users (within their own shop)
            'view users',
            'create users',
            'edit users',
            'delete users',

            // Products
            'view products',
            'create products',
            'edit products',
            'delete products',

            // Inventory
            'view inventory',
            'create inventory',
            'edit inventory',
            'delete inventory',

            // Transactions
            'view transactions',
            'create transactions',
            'edit transactions',
            'delete transactions',

            // Daily Summaries
            'view daily-summaries',
            'create daily-summaries',

            // Settings (own shop settings)
            'view settings',
            'edit settings',
        ]);

        // ─── 4. Staff — read + limited write ───────────────────────
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->syncPermissions([
            'view products',
            'view inventory',
            'view transactions',
            'create transactions',
            'view daily-summaries',
        ]);
    }
}
