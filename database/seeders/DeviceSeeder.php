<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Project;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        if ($projects->count() === 0) {
            $this->command->error('No projects found. Run ProjectSeeder first.');
            return;
        }

        $cities = [
            'Riyadh',
            'Jeddah',
            'Makkah',
            'Madinah',
            'Tabuk',
            'Qassim',
            'Hail',
            'Asir',
            'Jazan',
            'Najran',
            'Al Jouf',
            'Northern Borders',
        ];

        $manufacturers = [
            'Sirona',
            'Planmeca',
            'KaVo',
            'Cefla',
            'Siemens',
            'GE Healthcare',
            'Philips',
            'Carestream',
        ];

        $deviceNames = [
            'Dental Chair',
            'X-Ray Unit',
            'Intraoral Sensor',
            'Autoclave',
            'Ultrasonic Scaler',
            'Dental Compressor',
            'Suction Unit',
            'CBCT Scanner',
            'LED Curing Light',
            'Apex Locator',
        ];

        $statuses = ['active', 'inactive', 'under_maintenance', 'out_of_service'];

        for ($i = 1; $i <= 500; $i++) {

            $project = $projects->random();
            $deviceName = $deviceNames[array_rand($deviceNames)];

            Device::create([
                'serial_number' => strtoupper(Str::random(10)),
                'model' => 'Model-' . rand(100, 999),
                'manufacturer' => $manufacturers[array_rand($manufacturers)],
                'location' => 'Biomedical Department',
                'city' => $cities[array_rand($cities)],
                'installation_date' => Carbon::now()->subDays(rand(30, 1500)),
                'status' => $statuses[array_rand($statuses)],

                // JSON name (no translation package dependency)
                'name' => [
                    'en' => $deviceName,
                    'ar' => 'جهاز ' . $deviceName,
                ],

                // ربط المشروع
                'project_id' => $project->id,
            ]);
        }

        $this->command->info('✅ 500 devices seeded successfully.');
    }
}
