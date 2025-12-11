<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pm_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pm_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->date('performed_at');      // تاريخ تنفيذ الصيانة
            $table->string('engineer_name');   // اسم مهندس الصيانة
            $table->enum('status', ['ok', 'needs_parts', 'critical']); 
            $table->text('report')->nullable(); // تقرير الصيانة
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pm_records');
    }
};
