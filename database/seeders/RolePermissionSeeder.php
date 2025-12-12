<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions
        $permissions = [
            'view dashboard',

            'manage projects',
            'view projects',

            'manage devices',
            'view devices',

            'manage breakdowns',
            'view breakdowns',
            'assign breakdowns',

            'manage pm',
            'view pm',

            'manage spare parts',
            'view spare parts',

            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $engineer = Role::firstOrCreate(['name' => 'engineer']);
        $technician = Role::firstOrCreate(['name' => 'technician']);
        $viewer = Role::firstOrCreate(['name' => 'viewer']);

        // Assign permissions
        $admin->givePermissionTo(Permission::all());

        $engineer->givePermissionTo([
            'view dashboard',
            'view projects',
            'view devices',
            'manage breakdowns',
            'manage pm',
            'view spare parts',
        ]);

        $technician->givePermissionTo([
            'view dashboard',
            'view devices',
            'view breakdowns',
            'manage breakdowns',
        ]);

        $viewer->givePermissionTo([
            'view dashboard',
            'view projects',
            'view devices',
            'view breakdowns',
            'view pm',
            'view spare parts',
        ]);
    }
}
