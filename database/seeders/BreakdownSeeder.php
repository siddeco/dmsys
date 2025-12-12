<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Breakdown;
use App\Models\Device;
use App\Models\User;
use Carbon\Carbon;

class BreakdownSeeder extends Seeder
{
    public function run(): void
    {
        $devices = Device::all();
        $engineers = User::role('engineer')->get();
        $technicians = User::role('technician')->get();

        if ($devices->isEmpty() || $engineers->isEmpty() || $technicians->isEmpty()) {
            $this->command->error('Devices or users missing. Run previous seeders first.');
            return;
        }

        $statuses = ['open', 'in_progress', 'resolved'];

        for ($i = 1; $i <= 80; $i++) {

            $device = $devices->random();
            $engineer = $engineers->random();
            $technician = rand(0, 1) ? $technicians->random() : null;

            Breakdown::create([
                'device_id' => $device->id,
                'project_id' => $device->project_id,

                'reported_by' => $engineer->id,
                'assigned_to' => $technician?->id,

                'title' => 'Device malfunction reported',
                'description' => 'Automatic seeded breakdown for testing workflow.',

                'status' => $statuses[array_rand($statuses)],

                'reported_at' => Carbon::now()->subDays(rand(1, 90)),
            ]);
        }

        $this->command->info('✅ Breakdown requests seeded successfully.');
    }
}
