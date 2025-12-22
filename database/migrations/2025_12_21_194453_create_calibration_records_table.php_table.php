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
        Schema::create('calibration_records', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('calibrated_by')->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            // المعلومات الأساسية
            $table->string('certificate_number')->unique()->comment('رقم شهادة المعايرة');
            $table->string('calibration_standard')->nullable()->comment('المعيار المستخدم');

            // التواريخ
            $table->date('calibration_date');
            $table->date('next_calibration_date');
            $table->timestamp('calibrated_at')->nullable();

            // النتائج
            $table->enum('status', ['passed', 'failed', 'needs_adjustment', 'out_of_tolerance'])->default('passed');
            $table->json('measurements')->nullable()->comment('قياسات المعايرة');
            $table->json('tolerances')->nullable()->comment('حدود القبول');

            // المعدات المستخدمة
            $table->string('calibration_equipment')->nullable()->comment('معدات المعايرة المستخدمة');
            $table->string('equipment_certificate')->nullable()->comment('شهادة معايرة المعدات');

            // التوثيق
            $table->string('certificate_path')->nullable()->comment('مسار شهادة المعايرة');
            $table->string('report_path')->nullable()->comment('تقرير المعايرة');

            // التكاليف
            $table->decimal('calibration_cost', 10, 2)->nullable();
            $table->string('invoice_number')->nullable()->comment('رقم الفاتورة');

            // ملاحظات
            $table->text('notes')->nullable();
            $table->text('recommendations')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['device_id', 'status']);
            $table->index(['calibration_date', 'next_calibration_date']);
            $table->index('certificate_number');
            $table->index('calibrated_by');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('calibration_records');
    }
};