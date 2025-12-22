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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('supplier_id')->nullable()->constrained('users')->nullOnDelete();

            // المعلومات الأساسية
            $table->string('part_number')->unique()->comment('رقم القطعة');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('model_compatibility')->nullable()->comment('النماذج المتوافقة');

            // التصنيف
            $table->enum('category', [
                'electronic',
                'mechanical',
                'electrical',
                'optical',
                'hydraulic',
                'pneumatic',
                'consumable',
                'accessory',
                'other'
            ])->default('electronic');

            $table->enum('criticality', ['critical', 'important', 'standard', 'low'])->default('standard');

            // المخزون
            $table->integer('quantity')->default(0);
            $table->integer('minimum_stock')->default(5)->comment('الحد الأدنى للمخزون');
            $table->integer('reorder_quantity')->nullable()->comment('كمية إعادة الطلب');

            // الموقع
            $table->string('storage_location')->nullable()->comment('مكان التخزين');
            $table->string('bin_number')->nullable()->comment('رقم الصندوق/الرف');

            // الأسعار
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('selling_price', 10, 2)->nullable();

            // التتبع
            $table->date('purchase_date')->nullable();
            $table->date('expiry_date')->nullable()->comment('تاريخ الصلاحية إن وجد');
            $table->string('batch_number')->nullable()->comment('رقم الدفعة');

            // المواصفات
            $table->string('specifications')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('weight')->nullable();

            // الصور والوثائق
            $table->string('image_path')->nullable();
            $table->string('datasheet_path')->nullable()->comment('ورقة البيانات الفنية');

            // الحالة
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['in_stock', 'low_stock', 'out_of_stock', 'discontinued'])->default('in_stock');

            $table->softDeletes();
            $table->timestamps();

            // فهارس
            $table->index(['part_number', 'name']);
            $table->index(['category', 'criticality']);
            $table->index(['quantity', 'minimum_stock']);
            $table->index('status');
            $table->index('storage_location');
        });
    }

    /**
     * التراجع عن الهجرة
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};