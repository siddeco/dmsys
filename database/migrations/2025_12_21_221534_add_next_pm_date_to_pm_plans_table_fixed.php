<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pm_plans', function (Blueprint $table) {
            // أضف next_pm_date فقط (بدون after)
            if (!Schema::hasColumn('pm_plans', 'next_pm_date')) {
                $table->date('next_pm_date')->nullable();
            }

            // أضف status
            if (!Schema::hasColumn('pm_plans', 'status')) {
                $table->string('status')->default('pending');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pm_plans', function (Blueprint $table) {
            $table->dropColumn(['next_pm_date', 'status']);
        });
    }
};