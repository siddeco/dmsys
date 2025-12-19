<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spare_part_usages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('spare_part_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('breakdown_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('pm_record_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity');

            $table->enum('type', ['issue', 'return']); // صرف أو إرجاع

            $table->foreignId('performed_by')
                ->constrained('users');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_usages');
    }
};
