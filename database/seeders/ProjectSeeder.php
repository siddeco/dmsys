<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'name' => 'King Fahad Hospital',
                'client' => 'MOH',
                'city' => 'Riyadh',
                'description' => 'Main tertiary hospital project',
            ],
            [
                'name' => 'Tabuk Dental Complex',
                'client' => 'Private',
                'city' => 'Tabuk',
                'description' => 'Dental clinics project',
            ],
            [
                'name' => 'Jeddah Medical Center',
                'client' => 'MOH',
                'city' => 'Jeddah',
                'description' => 'General medical center',
            ],
            [
                'name' => 'Asir Central Hospital',
                'client' => 'MOH',
                'city' => 'Asir',
                'description' => 'Regional hospital',
            ],
            [
                'name' => 'Al Jouf Dental Center',
                'client' => 'Private',
                'city' => 'Al Jouf',
                'description' => 'Dental services center',
            ],
        ];

        foreach ($projects as $project) {
            Project::firstOrCreate(
                ['name' => $project['name']],
                $project
            );
        }
    }
}
