<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('spare_parts', function (Blueprint $table) {
        $table->id();

        $table->string('name');               // اسم القطعة
        $table->string('part_number')->nullable(); // رقم الصنف
        $table->string('manufacturer')->nullable(); // الشركة المصنعة

        // قد تكون القطعة خاصة بجهاز معيّن
        $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();

        $table->integer('quantity')->default(0);   // الكمية الحالية في المخزون
        $table->integer('alert_threshold')->default(5); // حد التنبيه

        $table->text('description')->nullable();

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('spare_parts');
}

};
