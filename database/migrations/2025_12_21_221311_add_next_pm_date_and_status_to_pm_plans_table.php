<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pm_plans', function (Blueprint $table) {
            // أضف next_pm_date إذا لم يكن موجوداً
            if (!Schema::hasColumn('pm_plans', 'next_pm_date')) {
                $table->date('next_pm_date')->nullable()->after('last_pm_date');
            }

            // أضف status إذا لم يكن موجوداً
            if (!Schema::hasColumn('pm_plans', 'status')) {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue'])
                    ->default('pending')
                    ->after('next_pm_date');
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