<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PmPlan;
use App\Models\Device;
use Carbon\Carbon;

class PmPlanSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();

        if ($devices->isEmpty()) {
            $this->command->error('No devices found. Run DeviceSeeder first.');
            return;
        }

        // 30% من الأجهزة
        $pmDevices = $devices->random((int) ceil($devices->count() * 0.30));

        foreach ($pmDevices as $device) {

            $interval = [3, 6, 12][array_rand([3, 6, 12])];

            PmPlan::create([
                'device_id' => $device->id,
                'interval_months' => $interval,
                'next_pm_date' => Carbon::now()->addMonths($interval),
                'notes' => 'Auto generated PM plan for testing.',
            ]);
        }

        $this->command->info('✅ PM Plans seeded for 30% of devices.');
    }
}
