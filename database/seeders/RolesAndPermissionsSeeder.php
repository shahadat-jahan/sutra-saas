<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::firstOrCreate(['name' => 'manage shops']);
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'view analytics']);

        // Create Roles and assign permissions
        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::firstOrCreate(['name' => 'shop-owner']);
        $role->givePermissionTo(['view analytics', 'manage users']);

        $role = Role::firstOrCreate(['name' => 'staff']);
        $role->givePermissionTo(['view analytics']);
    }
}
