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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // المعلومات الأساسية
            $table->string('name');
            $table->string('code')->nullable()->unique()->comment('رمز المشروع مثل PROJ-2024-001');

            // العميل
            $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('client_name')->nullable()->comment('للنسخ الاحتياطي');
            $table->enum('client_type', [
                'hospital',
                'clinic',
                'laboratory',
                'pharmacy',
                'government',
                'company',
                'other'
            ])->nullable();

            // الوصف
            $table->text('description')->nullable();

            // الموقع
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

            // التواريخ
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('actual_end_date')->nullable()->comment('تاريخ الانتهاء الفعلي');

            // الإدارة
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['active', 'completed', 'on_hold', 'cancelled'])->default('active');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');

            // المالية
            $table->decimal('budget', 15, 2)->nullable();
            $table->decimal('actual_cost', 15, 2)->nullable();

            // العقد
            $table->string('contract_number')->nullable();
            $table->decimal('contract_value', 15, 2)->nullable();
            $table->integer('warranty_period')->nullable()->comment('فترة الضمان بالأشهر');

            // الحالة
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['status', 'priority']);
            $table->index(['region', 'city']);
            $table->index(['client_type', 'is_active']);
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};