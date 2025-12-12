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

    $table->foreignId('device_id')->constrained()->cascadeOnDelete();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();

    // المستخدم الذي أنشأ البلاغ
    $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();

    // الفني المستلم
    $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

    $table->string('title');
    $table->text('description')->nullable();

    $table->enum('status', ['open', 'in_progress', 'resolved'])->default('open');

    $table->timestamp('reported_at')->nullable();

    $table->timestamps();
});

}

public function down()
{
    Schema::dropIfExists('breakdowns');
}


    
};
