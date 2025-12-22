<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * تشغيل الهجرة
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // المعلومات الأساسية
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('employee_id')->nullable()->unique()->comment('رقم الموظف/الفني');

            // نوع المستخدم والمنظمة
            $table->enum('user_type', ['company_staff', 'client_staff'])->default('company_staff');
            $table->string('organization_name')->nullable();
            $table->enum('organization_type', [
                'medical_company',  // شركة أجهزة طبية
                'hospital',         // مستشفى
                'clinic',           // عيادة
                'laboratory',       // مختبر
                'pharmacy',         // صيدلية
                'government',       // جهة حكومية
                'other'             // أخرى
            ])->nullable();

            // الموقع الجغرافي
            $table->string('city')->nullable();
            $table->enum('region', [
                'الرياض',
                'مكة المكرمة',
                'المدينة المنورة',
                'القصيم',
                'الشرقية',
                'عسير',
                'تبوك',
                'حائل',
                'الحدود الشمالية',
                'جازان',
                'نجران',
                'الباحة',
                'الجوف'
            ])->nullable();

            $table->text('address')->nullable();
            $table->text('notes')->nullable();

            // الحالة والنشاط
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();

            $table->rememberToken();
            $table->softDeletes(); // لحذف المستخدمين بشكل آمن
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index(['user_type', 'organization_type']);
            $table->index(['region', 'city']);
            $table->index(['employee_id', 'is_active']);
            $table->index('last_login_at');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};