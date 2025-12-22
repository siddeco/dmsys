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
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();

            // العلاقة مع المشروع
            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            // العلاقة مع المستخدم
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // دور المستخدم في المشروع
            $table->enum('role', [
                'project_manager',
                'supervisor',
                'lead_engineer',
                'field_technician',
                'quality_checker',
                'client_representative',
                'support_staff'
            ])->default('field_technician');

            // التفاصيل
            $table->date('assigned_date')->default(now());
            $table->date('unassigned_date')->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // منع التكرار
            $table->unique(['project_id', 'user_id']);

            // فهارس
            $table->index(['user_id', 'role']);
            $table->index('assigned_date');
            $table->index('unassigned_date');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};