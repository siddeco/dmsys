<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // الحصول على المستخدمين الموجودين بالفعل
        $adminUser = User::where('email', 'admin@example.com')->first();
        $clientUser = User::where('email', 'client@example.com')->first();
        $managerUser = User::where('email', 'manager@example.com')->first();
        $engineerUser = User::where('email', 'engineer@example.com')->first();
        $technicianUser = User::where('email', 'technician@example.com')->first();

        // إذا لم يكن هناك مستخدمين، أنشئهم أولاً
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $adminUser->assignRole('admin');
        }

        if (!$clientUser) {
            $clientUser = User::create([
                'name' => 'Client',
                'email' => 'client@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $clientUser->assignRole('client');
        }

        if (!$managerUser) {
            $managerUser = User::create([
                'name' => 'Project Manager',
                'email' => 'manager@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $managerUser->assignRole('project_manager');
        }

        if (!$engineerUser) {
            $engineerUser = User::create([
                'name' => 'Engineer',
                'email' => 'engineer@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $engineerUser->assignRole('engineer');
        }

        if (!$technicianUser) {
            $technicianUser = User::create([
                'name' => 'Technician',
                'email' => 'technician@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $technicianUser->assignRole('technician');
        }

        // إنشاء بعض المستخدمين الإضافيين للعملاء
        $clients = [
            [
                'name' => 'مستشفى الملك فهد',
                'email' => 'kingfahad@example.com',
                'role' => 'client'
            ],
            [
                'name' => 'مستشفى دلة',
                'email' => 'dallah@example.com',
                'role' => 'client'
            ],
            [
                'name' => 'مستشفى السلامة',
                'email' => 'alsalamah@example.com',
                'role' => 'client'
            ],
            [
                'name' => 'عيادة الصافي',
                'email' => 'alsafi@example.com',
                'role' => 'client'
            ],
            [
                'name' => 'مستشفى القوات المسلحة',
                'email' => 'military@example.com',
                'role' => 'client'
            ],
        ];

        foreach ($clients as $clientData) {
            $newClient = User::firstOrCreate(
                ['email' => $clientData['email']],
                [
                    'name' => $clientData['name'],
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]
            );

            if (!$newClient->hasRole('client')) {
                $newClient->assignRole('client');
            }
        }

        $projects = [
            // مشاريع في الرياض
            [
                'name' => 'مشروع صيانة أجهزة الأشعة - مستشفى الملك فهد',
                'code' => 'PROJ-2024-001',
                'client_id' => User::where('email', 'kingfahad@example.com')->first()->id,
                'client_name' => 'مستشفى الملك فهد',
                'client_type' => 'hospital',
                'description' => 'صيانة وقائية وعلاجية لأجهزة الأشعة السينية وأجهزة التصوير المقطعي',
                'city' => 'الرياض',
                'region' => 'الرياض',
                'address' => 'حي الملك فهد، الرياض',
                'start_date' => Carbon::now()->subMonths(3),
                'end_date' => Carbon::now()->addMonths(9),
                'project_manager_id' => $managerUser->id,
                'status' => 'active',
                'priority' => 'high',
                'budget' => 500000,
                'actual_cost' => 125000,
                'contract_number' => 'CON-2024-001',
                'contract_value' => 500000,
                'warranty_period' => 12,
                'notes' => 'مشروع حيوي يتطلب متابعة دورية',
                'is_active' => true,
            ],
            [
                'name' => 'مشروع تحديث أجهزة العناية المركزة - مستشفى دلة',
                'code' => 'PROJ-2024-002',
                'client_id' => User::where('email', 'dallah@example.com')->first()->id,
                'client_name' => 'مستشفى دلة',
                'client_type' => 'hospital',
                'description' => 'تحديث وصيانة أجهزة التنفس الصناعي وأجهزة المراقبة في أقسام العناية المركزة',
                'city' => 'الرياض',
                'region' => 'الرياض',
                'address' => 'حي العليا، الرياض',
                'start_date' => Carbon::now()->subMonths(1),
                'end_date' => Carbon::now()->addMonths(11),
                'project_manager_id' => $managerUser->id,
                'status' => 'active',
                'priority' => 'medium',
                'budget' => 350000,
                'actual_cost' => 45000,
                'contract_number' => 'CON-2024-002',
                'contract_value' => 350000,
                'warranty_period' => 18,
                'notes' => 'يتضمن تدريب الكادر الطبي على الأجهزة الجديدة',
                'is_active' => true,
            ],
            // مشاريع في جدة
            [
                'name' => 'مشروع صيانة أجهزة المختبر - مستشفى السلامة',
                'code' => 'PROJ-2024-003',
                'client_id' => User::where('email', 'alsalamah@example.com')->first()->id,
                'client_name' => 'مستشفى السلامة',
                'client_type' => 'hospital',
                'description' => 'صيانة أجهزة التحاليل المخبرية وأجهزة الطرد المركزي',
                'city' => 'جدة',
                'region' => 'مكة المكرمة',
                'address' => 'حي الصفا، جدة',
                'start_date' => Carbon::now()->subMonths(2),
                'end_date' => Carbon::now()->addMonths(10),
                'project_manager_id' => $managerUser->id,
                'status' => 'active',
                'priority' => 'medium',
                'budget' => 280000,
                'actual_cost' => 75000,
                'contract_number' => 'CON-2024-003',
                'contract_value' => 280000,
                'warranty_period' => 12,
                'notes' => 'مشروع يتطلب كفاءات عالية في الأجهزة المخبرية',
                'is_active' => true,
            ],
            // مشاريع في الشرقية
            [
                'name' => 'مشروع تجهيز عيادة الصافي بالأجهزة الطبية',
                'code' => 'PROJ-2024-004',
                'client_id' => User::where('email', 'alsafi@example.com')->first()->id,
                'client_name' => 'عيادة الصافي',
                'client_type' => 'clinic',
                'description' => 'تجهيز العيادة بأجهزة التشخيص والعلاج الأساسية',
                'city' => 'الخبر',
                'region' => 'الشرقية',
                'address' => 'حي الراكة، الخبر',
                'start_date' => Carbon::now()->subMonths(6),
                'end_date' => Carbon::now()->addMonths(6),
                'project_manager_id' => $managerUser->id,
                'status' => 'active',
                'priority' => 'low',
                'budget' => 150000,
                'actual_cost' => 135000,
                'contract_number' => 'CON-2024-004',
                'contract_value' => 150000,
                'warranty_period' => 24,
                'notes' => 'مشروع قيد التنفيذ، تم تسليم 90% من الأجهزة',
                'is_active' => true,
            ],
            [
                'name' => 'مشروع صيانة أجهزة الجراحة - مستشفى القوات المسلحة',
                'code' => 'PROJ-2024-005',
                'client_id' => User::where('email', 'military@example.com')->first()->id,
                'client_name' => 'مستشفى القوات المسلحة',
                'client_type' => 'hospital',
                'description' => 'صيانة أجهزة الجراحة المنظارية وأجهزة التخدير',
                'city' => 'الرياض',
                'region' => 'الرياض',
                'address' => 'حي السليمانية، الرياض',
                'start_date' => Carbon::now()->subMonths(4),
                'end_date' => Carbon::now()->addMonths(8),
                'project_manager_id' => $managerUser->id,
                'status' => 'active',
                'priority' => 'high',
                'budget' => 750000,
                'actual_cost' => 200000,
                'contract_number' => 'CON-2024-005',
                'contract_value' => 750000,
                'warranty_period' => 36,
                'notes' => 'مشروع عسكري يتطلب أعلى درجات السرية والجودة',
                'is_active' => true,
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // إضافة فريق المشروع
            $this->assignTeamToProject($project, $managerUser, $engineerUser, $technicianUser);

            $this->command->info("✅ تم إنشاء مشروع: {$project->name}");
        }

        $this->command->info('🎯 تم إنشاء ' . count($projects) . ' مشروع بنجاح');
    }

    private function assignTeamToProject(Project $project, $managerUser, $engineerUser, $technicianUser): void
    {
        // إضافة مدير المشروع
        if ($managerUser) {
            $project->teamMembers()->syncWithoutDetaching([
                $managerUser->id => [
                    'role' => 'project_manager',
                    'assigned_date' => Carbon::now()->subMonths(3),
                    'hourly_rate' => 200,
                ]
            ]);
        }

        // إضافة مهندس (لجميع المشاريع عدا المشروع الرابع)
        if ($engineerUser && $project->code !== 'PROJ-2024-004') {
            $project->teamMembers()->syncWithoutDetaching([
                $engineerUser->id => [
                    'role' => 'lead_engineer',
                    'assigned_date' => Carbon::now()->subMonths(2),
                    'hourly_rate' => 150,
                ]
            ]);
        }

        // إضافة فني
        if ($technicianUser) {
            $project->teamMembers()->syncWithoutDetaching([
                $technicianUser->id => [
                    'role' => 'field_technician',
                    'assigned_date' => Carbon::now()->subMonths(1),
                    'hourly_rate' => 100,
                ]
            ]);
        }

        // إضافة العميل كممثل للعميل
        if ($project->client) {
            $project->teamMembers()->syncWithoutDetaching([
                $project->client->id => [
                    'role' => 'client_representative',
                    'assigned_date' => $project->start_date,
                ]
            ]);
        }
    }
}