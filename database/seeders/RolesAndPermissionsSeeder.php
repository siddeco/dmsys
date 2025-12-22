<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // تنظيف الأدوار والصلاحيات القديمة
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // إنشاء الصلاحيات الأساسية
        $permissions = [
            // إدارة المستخدمين
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage users',

            // إدارة المشاريع
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            'manage projects',

            // إدارة الأجهزة
            'view devices',
            'create devices',
            'edit devices',
            'delete devices',
            'manage devices',

            // إدارة الصيانة
            'view breakdowns',
            'create breakdowns',
            'edit breakdowns',
            'resolve breakdowns',
            'manage breakdowns',
            'view pm plans',
            'create pm plans',
            'edit pm plans',
            'execute pm plans',
            'manage pm plans',

            // إدارة قطع الغيار
            'view spare parts',
            'create spare parts',
            'edit spare parts',
            'issue spare parts',
            'manage spare parts',

            // إدارة التقارير
            'view reports',
            'generate reports',
            'export reports',

            // إدارة النظام
            'manage system',
            'manage settings',
            'view audit logs',
        ];

        // إنشاء الصلاحيات باستخدام firstOrCreate لمنع التكرار
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // إنشاء أو جلب الأدوار
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $projectManagerRole = Role::firstOrCreate(['name' => 'project_manager', 'guard_name' => 'web']);
        $engineerRole = Role::firstOrCreate(['name' => 'engineer', 'guard_name' => 'web']);
        $technicianRole = Role::firstOrCreate(['name' => 'technician', 'guard_name' => 'web']);
        $clientRole = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // منح جميع الصلاحيات لـ admin
        $adminRole->syncPermissions(Permission::all());

        // صلاحيات project_manager
        $projectManagerRole->syncPermissions([
            'view projects',
            'create projects',
            'edit projects',
            'manage projects',
            'view devices',
            'create devices',
            'edit devices',
            'manage devices',
            'view breakdowns',
            'create breakdowns',
            'edit breakdowns',
            'resolve breakdowns',
            'view pm plans',
            'create pm plans',
            'edit pm plans',
            'execute pm plans',
            'view reports',
            'generate reports',
        ]);

        // صلاحيات engineer
        $engineerRole->syncPermissions([
            'view projects',
            'view devices',
            'edit devices',
            'view breakdowns',
            'create breakdowns',
            'edit breakdowns',
            'resolve breakdowns',
            'view pm plans',
            'execute pm plans',
            'view spare parts',
            'issue spare parts',
        ]);

        // صلاحيات technician
        $technicianRole->syncPermissions([
            'view projects',
            'view devices',
            'view breakdowns',
            'create breakdowns',
            'resolve breakdowns',
            'execute pm plans',
            'view spare parts',
            'issue spare parts',
        ]);

        // صلاحيات client
        $clientRole->syncPermissions([
            'view projects',
            'view devices',
            'view breakdowns',
            'create breakdowns',
        ]);

        $this->command->info('✅ تم إنشاء الصلاحيات والأدوار بنجاح!');
    }
}