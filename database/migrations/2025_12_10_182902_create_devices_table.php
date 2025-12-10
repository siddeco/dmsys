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
    Schema::create('devices', function (Blueprint $table) {
        $table->id();
        $table->string('serial_number')->unique();
        $table->string('model')->nullable();
        $table->string('manufacturer')->nullable();
        $table->string('location')->nullable();   // سنتحول إلى multilang لاحقًا
        $table->date('installation_date')->nullable();
        $table->json('name');
        $table->enum('status', ['active', 'inactive', 'under_maintenance', 'out_of_service'])
              ->default('active');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
