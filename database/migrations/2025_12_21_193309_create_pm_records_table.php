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
        Schema::create('pm_records', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('pm_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('performed_by')->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            // المعلومات الأساسية
            $table->string('record_number')->unique()->comment('مثل: PM-2024-001');
            $table->text('description')->nullable();

            // التواريخ
            $table->date('scheduled_date');
            $table->date('performed_date');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // النتائج
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'overdue'])->default('scheduled');
            $table->enum('outcome', ['passed', 'failed', 'needs_attention', 'partially_completed'])->nullable();

            // المعايرة والقياسات
            $table->json('measurements')->nullable()->comment('قياسات قبل وبعد الصيانة');
            $table->json('calibration_data')->nullable()->comment('بيانات المعايرة');

            // المهام المنفذة
            $table->json('completed_tasks')->nullable();
            $table->text('findings')->nullable()->comment('الملاحظات المكتشفة');
            $table->text('recommendations')->nullable()->comment('التوصيات');

            // الموارد
            $table->decimal('labor_hours', 5, 2)->nullable()->comment('ساعات العمل');
            $table->decimal('labor_cost', 10, 2)->nullable();
            $table->decimal('parts_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();

            // الموافقات والتوثيق
            $table->string('customer_signature_path')->nullable()->comment('توقيع العميل');
            $table->string('engineer_signature_path')->nullable()->comment('توقيع المهندس');
            $table->string('report_path')->nullable()->comment('تقرير PDF');

            // ملاحظات
            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['device_id', 'status']);
            $table->index(['performed_by', 'performed_date']);
            $table->index(['scheduled_date', 'completed_at']);
            $table->index('record_number');
            $table->index('outcome');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_records');
    }
};