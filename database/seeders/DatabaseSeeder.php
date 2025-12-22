<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Project;
use App\Models\Device;
use App\Models\Breakdown;
use App\Models\PmPlan;
use App\Models\PmRecord;
use App\Models\SparePart;
use App\Models\SparePartUsage;
use App\Models\CalibrationRecord;
use App\Models\ProjectDocument;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            ProjectSeeder::class,
            DeviceSeeder::class,
            MaintenanceSeeder::class,
            SparePartSeeder::class,
        ]);
    }
}