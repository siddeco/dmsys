<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // تأكد أن الأدوار موجودة
        $adminRole      = Role::where('name', 'admin')->first();
        $engineerRole   = Role::where('name', 'engineer')->first();
        $technicianRole = Role::where('name', 'technician')->first();
        $viewerRole     = Role::where('name', 'viewer')->first();

        // كلمة مرور موحدة
        $password = Hash::make('password');

        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@dmsys.test'],
            [
                'name' => 'System Admin',
                'password' => $password,
            ]
        );
        $admin->assignRole($adminRole);

        // Engineer
        $engineer = User::firstOrCreate(
            ['email' => 'engineer@dmsys.test'],
            [
                'name' => 'Maintenance Engineer',
                'password' => $password,
            ]
        );
        $engineer->assignRole($engineerRole);

        // Technician
        $technician = User::firstOrCreate(
            ['email' => 'tech@dmsys.test'],
            [
                'name' => 'Field Technician',
                'password' => $password,
            ]
        );
        $technician->assignRole($technicianRole);

        // Viewer
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@dmsys.test'],
            [
                'name' => 'Read Only User',
                'password' => $password,
            ]
        );
        $viewer->assignRole($viewerRole);
    }
}
