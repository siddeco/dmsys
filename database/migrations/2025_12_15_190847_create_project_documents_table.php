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
        Schema::create('project_documents', function (Blueprint $table) {
    $table->id();

    $table->foreignId('project_id')
          ->constrained()
          ->cascadeOnDelete();

    $table->string('type');      // contract, po, warranty ...
    $table->string('file_path'); // storage path
    $table->string('original_name')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};
