<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // حذف الكاش القديم
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Create Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Devices
            'view devices',
            'create devices',
            'edit devices',
            'delete devices',

            // Projects
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',

            // PM Plans
            'view pm plans',
            'create pm plans',
            'edit pm plans',
            'delete pm plans',

            // PM Records
            'view pm records',
            'create pm records',

            // Breakdowns
            'view breakdowns',
            'create breakdown',   // فقط المهندس
            'update breakdown',   // المهندس + الفني
            'assign breakdown',   // فقط المهندس

            // Spare Parts
            'view spare parts',
            'create spare parts',
            'update spare parts',
            'delete spare parts',

            // Dashboard
            'view dashboard',

            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
            'assign permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Roles + Assign Permissions
        |--------------------------------------------------------------------------
        */

        // Admin
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Engineer
        $engineer = Role::firstOrCreate(['name' => 'engineer']);
        $engineer->givePermissionTo([
            'view devices',
            'view projects',

            'view breakdowns',
            'create breakdown',
            'update breakdown',
            'assign breakdown',

            'view pm plans',
            'view pm records',
            'create pm records',

            'view spare parts',
            'view dashboard',
        ]);

        // Technician
        $technician = Role::firstOrCreate(['name' => 'technician']);
        $technician->givePermissionTo([
            'view devices',
            'view projects',

            'view pm plans',
            'view pm records',
            'create pm records',

            'view breakdowns',
            'update breakdown',   // بعد الإسناد

            'view dashboard',
        ]);

        // Viewer
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->givePermissionTo([
            'view devices',
            'view projects',
            'view breakdowns',
            'view pm plans',
            'view pm records',
            'view dashboard',
        ]);
    }
}
