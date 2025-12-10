<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('device_translations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('device_id');
        
        $table->string('locale')->index(); // ar, en
        
        $table->string('name');            // اسم الجهاز
        $table->text('description')->nullable();

        $table->unique(['device_id', 'locale']);
        $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_translations');
    }
};
