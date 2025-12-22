<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // المستخدمون من شركة طبية (Company Staff)
        $companyUsers = [
            [
                'name' => 'أحمد العتيبي',
                'email' => 'admin@medicalco.com',
                'password' => Hash::make('password'),
                'phone' => '+966500123456',
                'employee_id' => 'EMP-001',
                'user_type' => 'company_staff',
                'organization_name' => 'الشركة الطبية المتقدمة',
                'organization_type' => 'medical_company',
                'city' => 'الرياض',
                'region' => 'الرياض',
                'address' => 'حي النخيل، الرياض',
                'is_active' => true,
                'role' => 'admin',
            ],
            [
                'name' => 'سارة القحطاني',
                'email' => 'project.manager@medicalco.com',
                'password' => Hash::make('password'),
                'phone' => '+966511234567',
                'employee_id' => 'EMP-002',
                'user_type' => 'company_staff',
                'organization_name' => 'الشركة الطبية المتقدمة',
                'organization_type' => 'medical_company',
                'city' => 'الرياض',
                'region' => 'الرياض',
                'address' => 'حي النخيل، الرياض',
                'is_active' => true,
                'role' => 'project_manager',
            ],
            [
                'name' => 'خالد الحربي',
                'email' => 'engineer@medicalco.com',
                'password' => Hash::make('password'),
                'phone' => '+966522345678',
                'employee_id' => 'EMP-003',
                'user_type' => 'company_staff',
                'organization_name' => 'الشركة الطبية المتقدمة',
                'organization_type' => 'medical_company',
                'city' => 'جدة',
                'region' => 'مكة المكرمة',
                'address' => 'حي السلامة، جدة',
                'is_active' => true,
                'role' => 'engineer',
            ],
            [
                'name' => 'محمد الغامدي',
                'email' => 'technician@medicalco.com',
                'password' => Hash::make('password'),
                'phone' => '+966533456789',
                'employee_id' => 'EMP-004',
                'user_type' => 'company_staff',
                'organization_name' => 'الشركة الطبية المتقدمة',
                'organization_type' => 'medical_company',
                'city' => 'الدمام',
                'region' => 'الشرقية',
                'address' => 'حي النعيم، الدمام',
                'is_active' => true,
                'role' => 'technician',
            ],
        ];

        foreach ($companyUsers as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::create($userData);
            $user->assignRole($role);
        }

        // المستخدمون من المستشفيات (Client Staff)
        $hospitalUsers = [
            [
                'name' => 'د. عبدالله الفهد',
                'email' => 'director@kingfahad.com',
                'password' => Hash::make('password'),
                'phone' => '+966544567890',
                'employee_id' => 'HOSP-001',
                'user_type' => 'client_staff',
                'organization_name' => 'مستشفى الملك فهد',
                'organization_type' => 'hospital',
                'city' => 'الرياض',
                'region' => 'الرياض',
                'address' => 'حي الملك فهد، الرياض',
                'is_active' => true,
                'role' => 'client',
            ],
            [
                'name' => 'م. علي الزهراني',
                'email' => 'biomed@alsalamah.com',
                'password' => Hash::make('password'),
                'phone' => '+966555678901',
                'employee_id' => 'HOSP-002',
                'user_type' => 'client_staff',
                'organization_name' => 'مستشفى السلامة',
                'organization_type' => 'hospital',
                'city' => 'جدة',
                'region' => 'مكة المكرمة',
                'address' => 'حي الصفا، جدة',
                'is_active' => true,
                'role' => 'client',
            ],
            [
                'name' => 'أ. فاطمة العلي',
                'email' => 'admin@dallah.com',
                'password' => Hash::make('password'),
                'phone' => '+966566789012',
                'employee_id' => 'HOSP-003',
                'user_type' => 'client_staff',
                'organization_name' => 'مستشفى دلة',
                'organization_type' => 'hospital',
                'city' => 'الرياض',
                'region' => 'الرياض',
                'address' => 'حي العليا، الرياض',
                'is_active' => true,
                'role' => 'client',
            ],
        ];

        foreach ($hospitalUsers as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::create($userData);
            $user->assignRole($role);
        }

        // موظفو عيادات
        $clinicUsers = [
            [
                'name' => 'د. نورة السديري',
                'email' => 'director@alsafi.com',
                'password' => Hash::make('password'),
                'phone' => '+966577890123',
                'employee_id' => 'CLIN-001',
                'user_type' => 'client_staff',
                'organization_name' => 'عيادة الصافي',
                'organization_type' => 'clinic',
                'city' => 'الخبر',
                'region' => 'الشرقية',
                'address' => 'حي الراكة، الخبر',
                'is_active' => true,
                'role' => 'client',
            ],
        ];

        foreach ($clinicUsers as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::create($userData);
            $user->assignRole($role);
        }

        $this->command->info('✅ تم إنشاء ' . (count($companyUsers) + count($hospitalUsers) + count($clinicUsers)) . ' مستخدم بنجاح');
    }
}