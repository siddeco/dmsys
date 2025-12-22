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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('project_id')
                ->nullable()
                ->constrained('projects')
                ->nullOnDelete();

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // المعلومات الأساسية
            $table->string('serial_number')->unique();
            $table->json('name')->nullable(); // للترجمة
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();

            // التصنيف
            $table->enum('device_type', [
                'xray',
                'ultrasound',
                'mri',
                'ct_scanner',
                'ventilator',
                'monitor',
                'defibrillator',
                'analyzer',
                'centrifuge',
                'microscope',
                'autoclave',
                'incubator',
                'other'
            ])->nullable();

            $table->enum('category', [
                'imaging',
                'monitoring',
                'laboratory',
                'therapeutic',
                'surgical',
                'diagnostic',
                'dental',
                'ophthalmic',
                'other'
            ])->nullable();

            // الموقع
            $table->string('location')->nullable();
            $table->string('room_number')->nullable();
            $table->string('floor')->nullable();
            $table->string('building')->nullable();
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

            // التواريخ
            $table->date('purchase_date')->nullable();
            $table->date('installation_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->date('last_calibration_date')->nullable();
            $table->date('next_calibration_date')->nullable();

            // الحالة
            $table->enum('status', ['active', 'inactive', 'under_maintenance', 'out_of_service'])
                ->default('active');
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor'])->default('good');
            $table->boolean('is_archived')->default(false);

            // المواصفات الفنية
            $table->string('power_requirements')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();
            $table->json('specifications')->nullable();

            // المالية
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->decimal('current_value', 15, 2)->nullable();
            $table->decimal('depreciation_rate', 5, 2)->nullable();

            // الضمان والصيانة
            $table->string('service_provider')->nullable();
            $table->string('service_contract_number')->nullable();
            $table->integer('preventive_maintenance_frequency')->nullable()->comment('بالأيام');

            // ملاحظات
            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['project_id', 'status']);
            $table->index(['device_type', 'category']);
            $table->index(['assigned_to', 'is_archived']);
            $table->index('warranty_expiry');
            $table->index('next_calibration_date');
            $table->index('serial_number');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};