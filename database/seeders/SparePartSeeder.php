<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SparePart;
use App\Models\Device;
use Illuminate\Support\Str;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();

        if ($devices->count() === 0) {
            $this->command->error('No devices found. Run DeviceSeeder first.');
            return;
        }

        $parts = [
            'Power Supply',
            'X-Ray Sensor Cable',
            'Foot Control',
            'Water Pump',
            'Vacuum Motor',
            'Control Board',
            'Display Screen',
            'Handpiece Tubing',
            'Fuse',
            'Cooling Fan',
        ];

        foreach ($parts as $partName) {

            $lowStock = rand(0, 1) === 1;

            SparePart::create([
                'name' => $partName,
                'part_number' => strtoupper(Str::random(8)),
                'manufacturer' => ['Sirona','Planmeca','KaVo','Cefla'][array_rand(['Sirona','Planmeca','KaVo','Cefla'])],

                // ربط عشوائي ببعض الأجهزة
                'device_id' => rand(0, 1) ? $devices->random()->id : null,

                // كمية منخفضة لاختبار التنبيه
                'quantity' => $lowStock ? rand(0, 3) : rand(6, 30),
                'alert_threshold' => 5,

                'description' => $lowStock
                    ? 'Low stock test item'
                    : 'Regular spare part',
            ]);
        }

        $this->command->info('✅ Spare parts seeded successfully (including low stock items).');
    }
}
