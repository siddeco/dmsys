<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        // الحصول على المستخدمين
        $technician = User::where('email', 'technician@example.com')->first();
        $engineer = User::where('email', 'engineer@example.com')->first();

        if (!$technician || !$engineer) {
            $this->command->error('❌ المستخدمون غير موجودين. قم بتشغيل UsersSeeder أولاً.');
            return;
        }

        $projects = Project::all();

        foreach ($projects as $project) {
            $devices = $this->getDevicesForProject($project, $technician, $engineer);

            foreach ($devices as $deviceData) {
                Device::firstOrCreate(
                    ['serial_number' => $deviceData['serial_number']],
                    $deviceData
                );

                $this->command->info("✅ تم إنشاء/تحديث جهاز: {$deviceData['serial_number']} للمشروع: {$project->name}");
            }
        }

        $totalDevices = Device::count();
        $this->command->info("🎯 تم إنشاء/تحديث {$totalDevices} جهاز بنجاح");
    }

    private function getDevicesForProject(Project $project, $technician, $engineer): array
    {
        $technicianId = $technician->id;
        $engineerId = $engineer->id;

        switch ($project->code) {
            case 'PROJ-2024-001': // مستشفى الملك فهد - أجهزة أشعة
                return [
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $technicianId,
                        'serial_number' => 'XRAY-2024-001',
                        'name' => json_encode(['en' => 'X-Ray Machine', 'ar' => 'جهاز الأشعة السينية']),
                        'model' => 'Digital Diagnost',
                        'manufacturer' => 'Philips',
                        'device_type' => 'xray',
                        'category' => 'imaging',
                        'location' => 'قسم الأشعة - الطابق الأول',
                        'room_number' => 'RAD-101',
                        'floor' => 'الأول',
                        'building' => 'مبنى الأشعة الرئيسي',
                        'city' => $project->city,
                        'region' => $project->region,
                        'purchase_date' => Carbon::now()->subYears(2)->subMonths(1),
                        'installation_date' => Carbon::now()->subYears(2),
                        'warranty_expiry' => Carbon::now()->addMonths(6),
                        'last_calibration_date' => Carbon::now()->subMonths(1),
                        'next_calibration_date' => Carbon::now()->addMonths(3),
                        'status' => 'active',
                        'condition' => 'good',
                        'is_archived' => false,
                        'power_requirements' => '220V / 15A',
                        'dimensions' => '2.5m x 2m x 1.8m',
                        'weight' => '1200 kg',
                        'specifications' => json_encode([
                            'voltage' => '220V',
                            'current' => '15A',
                            'frequency' => '50Hz',
                            'max_power' => '3300W'
                        ]),
                        'purchase_price' => 450000,
                        'current_value' => 350000,
                        'depreciation_rate' => 10,
                        'service_provider' => 'Philips Healthcare',
                        'service_contract_number' => 'SC-2024-001',
                        'preventive_maintenance_frequency' => 90,
                        'notes' => 'جهاز يعمل بكفاءة عالية، يحتاج لصيانة دورية',
                    ],
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $engineerId,
                        'serial_number' => 'CT-2024-001',
                        'name' => json_encode(['en' => 'CT Scanner', 'ar' => 'جهاز التصوير المقطعي']),
                        'model' => 'Ingenuity Core 128',
                        'manufacturer' => 'Philips',
                        'device_type' => 'ct_scanner',
                        'category' => 'imaging',
                        'location' => 'قسم الأشعة - الطابق الأول',
                        'room_number' => 'RAD-102',
                        'floor' => 'الأول',
                        'building' => 'مبنى الأشعة الرئيسي',
                        'city' => $project->city,
                        'region' => $project->region,
                        'purchase_date' => Carbon::now()->subYears(1)->subMonths(1),
                        'installation_date' => Carbon::now()->subYears(1),
                        'warranty_expiry' => Carbon::now()->addMonths(12),
                        'last_calibration_date' => Carbon::now()->subMonths(3),
                        'next_calibration_date' => Carbon::now()->addMonths(6),
                        'status' => 'active',
                        'condition' => 'excellent',
                        'is_archived' => false,
                        'power_requirements' => '380V / 25A',
                        'dimensions' => '3m x 2.5m x 2.2m',
                        'weight' => '2500 kg',
                        'specifications' => json_encode([
                            'voltage' => '380V',
                            'current' => '25A',
                            'frequency' => '50Hz',
                            'max_power' => '9500W',
                            'slices' => 128
                        ]),
                        'purchase_price' => 1200000,
                        'current_value' => 1100000,
                        'depreciation_rate' => 8,
                        'service_provider' => 'Philips Healthcare',
                        'service_contract_number' => 'SC-2024-002',
                        'preventive_maintenance_frequency' => 60,
                        'notes' => 'أحدث جهاز في القسم، يحتاج مراقبة مستمرة',
                    ],
                ];

            case 'PROJ-2024-002': // مستشفى دلة - أجهزة عناية مركزة
                return [
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $technicianId,
                        'serial_number' => 'VENT-2024-001',
                        'name' => json_encode(['en' => 'Ventilator', 'ar' => 'جهاز التنفس الصناعي']),
                        'model' => 'V60',
                        'manufacturer' => 'Philips',
                        'device_type' => 'ventilator',
                        'category' => 'therapeutic',
                        'location' => 'ICU - السرير 1',
                        'room_number' => 'ICU-101',
                        'floor' => 'الثالث',
                        'building' => 'مبنى العناية المركزة',
                        'city' => $project->city,
                        'region' => $project->region,
                        'purchase_date' => Carbon::now()->subMonths(7),
                        'installation_date' => Carbon::now()->subMonths(6),
                        'warranty_expiry' => Carbon::now()->addMonths(18),
                        'last_calibration_date' => Carbon::now()->subMonths(1),
                        'next_calibration_date' => Carbon::now()->addMonths(2),
                        'status' => 'active',
                        'condition' => 'good',
                        'is_archived' => false,
                        'power_requirements' => '110V / 5A',
                        'dimensions' => '60cm x 40cm x 30cm',
                        'weight' => '25 kg',
                        'specifications' => json_encode([
                            'voltage' => '110V',
                            'battery_backup' => '4 hours',
                            'modes' => ['CMV', 'SIMV', 'PSV']
                        ]),
                        'purchase_price' => 85000,
                        'current_value' => 80000,
                        'depreciation_rate' => 15,
                        'service_provider' => 'Philips Healthcare',
                        'service_contract_number' => 'SC-2024-003',
                        'preventive_maintenance_frequency' => 30,
                        'notes' => 'جهاز حيوي، فحوصات أسبوعية مطلوبة',
                    ],
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $engineerId,
                        'serial_number' => 'MON-2024-001',
                        'name' => json_encode(['en' => 'Patient Monitor', 'ar' => 'جهاز مراقبة العلامات الحيوية']),
                        'model' => 'IntelliVue MX700',
                        'manufacturer' => 'Philips',
                        'device_type' => 'monitor',
                        'category' => 'monitoring',
                        'location' => 'ICU - السرير 1',
                        'room_number' => 'ICU-101',
                        'floor' => 'الثالث',
                        'building' => 'مبنى العناية المركزة',
                        'city' => $project->city,
                        'region' => $project->region,
                        'purchase_date' => Carbon::now()->subMonths(7),
                        'installation_date' => Carbon::now()->subMonths(6),
                        'warranty_expiry' => Carbon::now()->addMonths(18),
                        'last_calibration_date' => Carbon::now()->subMonths(2),
                        'next_calibration_date' => Carbon::now()->addMonths(1),
                        'status' => 'active',
                        'condition' => 'excellent',
                        'is_archived' => false,
                        'power_requirements' => '110V / 3A',
                        'dimensions' => '40cm x 30cm x 20cm',
                        'weight' => '8 kg',
                        'specifications' => json_encode([
                            'parameters' => ['ECG', 'SPO2', 'NIBP', 'Temp'],
                            'screen_size' => '15 inch'
                        ]),
                        'purchase_price' => 45000,
                        'current_value' => 42000,
                        'depreciation_rate' => 20,
                        'service_provider' => 'Philips Healthcare',
                        'service_contract_number' => 'SC-2024-004',
                        'preventive_maintenance_frequency' => 45,
                        'notes' => 'مراقبة مستمرة للعلامات الحيوية',
                    ],
                ];

            case 'PROJ-2024-003': // مستشفى السلامة - أجهزة مختبر
                return [
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $technicianId,
                        'serial_number' => 'LAB-2024-001',
                        'name' => json_encode(['en' => 'Blood Analyzer', 'ar' => 'جهاز تحليل الدم']),
                        'model' => 'Cobas 6000',
                        'manufacturer' => 'Roche',
                        'device_type' => 'analyzer',
                        'category' => 'laboratory',
                        'location' => 'المختبر المركزي',
                        'room_number' => 'LAB-201',
                        'floor' => 'الثاني',
                        'building' => 'مبنى المختبرات',
                        'city' => $project->city,
                        'region' => $project->region,
                        'purchase_date' => Carbon::now()->subYears(3)->subMonths(2),
                        'installation_date' => Carbon::now()->subYears(3),
                        'warranty_expiry' => Carbon::now()->subMonths(6),
                        'last_calibration_date' => Carbon::now()->subMonths(8),
                        'next_calibration_date' => Carbon::now()->addDays(7),
                        'status' => 'under_maintenance',
                        'condition' => 'poor',
                        'is_archived' => false,
                        'power_requirements' => '220V / 10A',
                        'dimensions' => '1.5m x 1m x 0.8m',
                        'weight' => '300 kg',
                        'specifications' => json_encode([
                            'throughput' => '600 tests/hour',
                            'sample_type' => '血清, بلازما, بوال'
                        ]),
                        'purchase_price' => 300000,
                        'current_value' => 180000,
                        'depreciation_rate' => 15,
                        'service_provider' => 'Roche Diagnostics',
                        'service_contract_number' => 'SC-2023-001',
                        'preventive_maintenance_frequency' => 180,
                        'notes' => 'الجهاز يحتاج صيانة عاجلة، ضمان منتهي',
                    ],
                ];

            case 'PROJ-2024-004': // عيادة الصافي - أجهزة عيادة
                return [
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $technicianId,
                        'serial_number' => 'ECG-2024-001',
                        'name' => json_encode(['en' => 'ECG Machine', 'ar' => 'جهاز تخطيط القلب']),
                        'model' => 'CardioTouch 3000',
                        'manufacturer' => 'Bionet',
                        'device_type' => 'other',
                        'category' => 'diagnostic',
                        'location' => 'غرفة الفحص',
                        'room_number' => 'EXAM-01',
                        'floor' => 'الأرضي',
                        'building' => 'المبنى الرئيسي',
                        'city' => $project->city,
                        'region' => $project->region,
                        'purchase_date' => Carbon::now()->subMonths(3),
                        'installation_date' => Carbon::now()->subMonths(2),
                        'warranty_expiry' => Carbon::now()->addMonths(22),
                        'last_calibration_date' => Carbon::now()->subMonths(2),
                        'next_calibration_date' => Carbon::now()->addMonths(8),
                        'status' => 'active',
                        'condition' => 'excellent',
                        'is_archived' => false,
                        'power_requirements' => '110V / 2A',
                        'dimensions' => '40cm x 30cm x 15cm',
                        'weight' => '5 kg',
                        'specifications' => json_encode([
                            'channels' => 12,
                            'paper_speed' => '25/50 mm/s'
                        ]),
                        'purchase_price' => 15000,
                        'current_value' => 14500,
                        'depreciation_rate' => 10,
                        'service_provider' => 'Bionet Middle East',
                        'service_contract_number' => 'SC-2024-005',
                        'preventive_maintenance_frequency' => 365,
                        'notes' => 'جهاز جديد، يعمل بكفاءة',
                    ],
                ];

            case 'PROJ-2024-005': // مستشفى القوات المسلحة - أجهزة جراحة
                return [
                    [
                        'project_id' => $project->id,
                        'assigned_to' => $engineerId,
                        'serial_number' => 'SURG-2024-001',
                        'name' => json_encode(['en' => 'Laparoscopic System', 'ar' => 'جهاز الجراحة المنظارية']),
                        'model' => 'EndoEye 4K',
                        'manufacturer' => 'Olympus',
                        'device_type' => 'other', // ✅ تغيير من 'surgical' إلى 'other'
                        'category' => 'surgical',
                        'location' => 'غرفة العمليات 1',
                        'room_number' => 'OR-01',
                        'floor' => 'الرابع',
                        'building' => 'مبنى العمليات',
                        'city' => $project->city,
                        'region' => $project->region,
                        'purchase_date' => Carbon::now()->subMonths(5),
                        'installation_date' => Carbon::now()->subMonths(4),
                        'warranty_expiry' => Carbon::now()->addMonths(32),
                        'last_calibration_date' => Carbon::now()->subMonths(4),
                        'next_calibration_date' => Carbon::now()->addMonths(6),
                        'status' => 'active',
                        'condition' => 'excellent',
                        'is_archived' => false,
                        'power_requirements' => '220V / 8A',
                        'dimensions' => '1.8m x 1m x 0.6m',
                        'weight' => '150 kg',
                        'specifications' => json_encode([
                            'resolution' => '4K UHD',
                            'light_source' => 'LED 300W'
                        ]),
                        'purchase_price' => 800000,
                        'current_value' => 780000,
                        'depreciation_rate' => 5,
                        'service_provider' => 'Olympus Medical',
                        'service_contract_number' => 'SC-2024-006',
                        'preventive_maintenance_frequency' => 90,
                        'notes' => 'جهاز جراحة متطور، فحص أسبوعي إلزامي',
                    ],
                ];


            default:
                return [];
        }
    }
}