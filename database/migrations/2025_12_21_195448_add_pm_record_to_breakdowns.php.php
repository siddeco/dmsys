<?php
// database/migrations/2024_12_20_000011_add_pm_record_to_breakdowns.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            // إضافة العلاقة بعد إنشاء جميع الجداول
            $table->foreignId('pm_record_id')
                ->nullable()
                ->constrained('pm_records')
                ->nullOnDelete()
                ->after('assigned_to');
        });
    }

    public function down(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            $table->dropForeign(['pm_record_id']);
            $table->dropColumn('pm_record_id');
        });
    }
};