<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء المستخدمين
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        $projectManager = User::create([
            'name' => 'Project Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $projectManager->assignRole('project_manager');

        $engineer = User::create([
            'name' => 'Engineer',
            'email' => 'engineer@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $engineer->assignRole('engineer');

        $technician = User::create([
            'name' => 'Technician',
            'email' => 'technician@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $technician->assignRole('technician');

        $client = User::create([
            'name' => 'Client',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $client->assignRole('client');
    }
}