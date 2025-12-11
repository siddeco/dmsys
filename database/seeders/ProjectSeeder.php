<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        $projects = [
            ['name' => 'Riyadh Dental Project', 'client' => 'MOH', 'city' => 'Riyadh'],
            ['name' => 'Jeddah Imaging Center', 'client' => 'Private Clinic', 'city' => 'Jeddah'],
            ['name' => 'Tabuk Dental Center', 'client' => 'MOH', 'city' => 'Tabuk'],
            ['name' => 'Qassim Medical Project', 'client' => 'Government', 'city' => 'Qassim'],
            ['name' => 'Makkah Sterilization Unit', 'client' => 'Hospital', 'city' => 'Makkah'],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
