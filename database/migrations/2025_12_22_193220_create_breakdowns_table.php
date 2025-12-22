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
        Schema::create('breakdowns', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('reported_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pm_record_id')->nullable()->constrained()->nullOnDelete();

            // المعلومات الأساسية
            $table->string('ticket_number')->unique()->comment('مثل: BD-2024-001');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['open', 'assigned', 'in_progress', 'resolved', 'closed'])->default('open');

            // التواريخ
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            // الحل
            $table->text('resolution')->nullable();
            $table->text('engineer_report')->nullable();
            $table->integer('downtime_days')->nullable()->default(0);

            // التكاليف
            $table->decimal('labor_cost', 10, 2)->nullable();
            $table->decimal('parts_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();

            // التقييم
            $table->enum('satisfaction_level', ['very_poor', 'poor', 'fair', 'good', 'excellent'])->nullable();
            $table->text('customer_feedback')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['device_id', 'status']);
            $table->index(['priority', 'status']);
            $table->index(['reported_by', 'assigned_to']);
            $table->index('ticket_number');
            $table->index('reported_at');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('breakdowns');
    }
};