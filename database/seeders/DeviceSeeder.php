<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            'Riyadh', 'Jeddah', 'Makkah', 'Madinah', 'Tabuk', 'Qassim',
            'Jazan', 'Najran', 'Asir', 'Hail', 'Northern Borders', 'Al Jouf'
        ];

        $manufacturers = [
            'Stern Weber', 'Anthos', 'Castellini', 'MyRay', 'Euronda', 
            'Siemens', 'Philips', 'Olympus', 'Mindray', 'GE Healthcare',
            'Samsung Medison'
        ];

        $models = [
            'S200', 'A7 Plus', 'E9 Med', 'HD Sensor', 'Turbo Smart', 
            'LED.H', 'Root ZX II', 'Ultrasound U50', 'ECG 300G', 'MRI Vision 7'
        ];

        $statuses = ['active', 'inactive', 'under_maintenance', 'out_of_service'];

        for ($i = 1; $i <= 50; $i++) {

            $city = $cities[array_rand($cities)];
            $model = $models[array_rand($models)];
            $manufacturer = $manufacturers[array_rand($manufacturers)];
            $status = $statuses[array_rand($statuses)];

            Device::create([
                'serial_number' => strtoupper(Str::random(10)),
                'model' => $model,
                'manufacturer' => $manufacturer,
                'location' => $city, // الموقع = مدينة سعودية
                'installation_date' => now()->subDays(rand(30, 1500)),
                'status' => $status,
                'project_id' => rand(1, 5), // يرتبط بمشاريع عشوائية (إذا لديك 5 مشاريع مثلاً)
                'name' => [
                    'en' => $manufacturer . ' ' . $model,
                    'ar' => 'جهاز ' . $model
                ]
            ]);
        }
    }
}
