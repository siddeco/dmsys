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
        Schema::create('spare_part_usages', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('spare_part_id')->constrained()->cascadeOnDelete();
            $table->foreignId('breakdown_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pm_record_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('performed_by')->constrained('users');

            // المعلومات الأساسية
            $table->string('transaction_number')->unique()->comment('مثل: SPU-2024-001');
            $table->enum('type', ['issue', 'return', 'adjustment', 'purchase', 'waste'])->default('issue');
            $table->integer('quantity');

            // التواريخ
            $table->date('transaction_date');
            $table->timestamp('recorded_at')->useCurrent();

            // التفاصيل
            $table->text('reason')->nullable()->comment('سبب الإصدار أو الإرجاع');
            $table->text('notes')->nullable();

            // التكاليف
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();

            // التخزين
            $table->string('from_location')->nullable()->comment('من موقع التخزين');
            $table->string('to_location')->nullable()->comment('إلى موقع التخزين');

            // التوثيق
            $table->string('reference_number')->nullable()->comment('رقم المرجع (فاتورة، طلبية)');
            $table->string('document_path')->nullable()->comment('وثيقة الإثبات');

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['spare_part_id', 'type']);
            $table->index(['breakdown_id', 'pm_record_id']);
            $table->index(['transaction_date', 'performed_by']);
            $table->index('transaction_number');
            $table->index('type');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_usages');
    }
};