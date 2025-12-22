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
        Schema::create('pm_plans', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users');

            // المعلومات الأساسية
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'half_yearly', 'yearly', 'custom']);
            $table->integer('custom_days')->nullable()->comment('إذا كان التكرار مخصص');

            // التواريخ
            $table->date('start_date');
            $table->date('next_due_date');
            $table->date('last_performed_date')->nullable();

            // المهام
            $table->json('tasks')->nullable()->comment('قائمة المهام JSON');
            $table->text('instructions')->nullable();

            // الحالة
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['pending', 'overdue', 'completed'])->default('pending');

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['device_id', 'is_active']);
            $table->index(['frequency', 'next_due_date']);
            $table->index('status');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('pm_plans');
    }
};