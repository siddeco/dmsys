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
    Schema::create('breakdowns', function (Blueprint $table) {
        $table->id();

        // الجهاز المرتبط بالعطل
        $table->foreignId('device_id')->constrained()->onDelete('cascade');

        // المشروع المستهدف (نسحبه من الجهاز)
        $table->foreignId('project_id')->constrained()->onDelete('cascade');

        // وصف المشكلة
        $table->text('issue_description');

        // حالة البلاغ
        $table->enum('status', [
            'new',          // تم إنشاء البلاغ
            'assigned',     // تم إسناده لفني
            'in_progress',  // الفني بدأ العمل
            'completed',    // تمت المعالجة
        ])->default('new');

        // إسناد الفني
        $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamp('assigned_at')->nullable();

        // تقرير الفني
        $table->text('engineer_report')->nullable();

        // وقت الإصلاح
        $table->timestamp('completed_at')->nullable();

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('breakdowns');
}


    
};
