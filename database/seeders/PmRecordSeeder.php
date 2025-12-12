<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PmRecord;
use App\Models\PmPlan;
use App\Models\User;
use Carbon\Carbon;

class PmRecordSeeder extends Seeder
{
    public function run(): void
    {
        $plans = PmPlan::all();
        $engineers = User::role('engineer')->get();

        if ($plans->isEmpty() || $engineers->isEmpty()) {
            $this->command->error('PM Plans or Engineers missing.');
            return;
        }

        foreach ($plans as $plan) {

            $recordsCount = rand(1, 3);

            for ($i = 1; $i <= $recordsCount; $i++) {

                $performedAt = Carbon::now()->subMonths(rand(1, 18));
                $engineer = $engineers->random();

                PmRecord::create([
                    'pm_plan_id' => $plan->id,
                    'device_id' => $plan->device_id,
                    'engineer_name' => $engineer->name,
                    'performed_at' => $performedAt,
                    'status' => ['ok', 'needs_parts', 'critical'][array_rand(['ok', 'needs_parts', 'critical'])],
                    'report' => 'Auto generated PM record for testing.',
                ]);

                // تحديث تاريخ الصيانة القادمة
                $plan->update([
                    'next_pm_date' => $performedAt->copy()->addMonths($plan->interval_months),
                ]);
            }
        }

        $this->command->info('✅ PM Records seeded successfully.');
    }
}
