<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pm_plans', function (Blueprint $table) {
            // التحقق من وجود الأعمدة قبل إضافتها
            $columns = Schema::getColumnListing('pm_plans');

            if (!in_array('next_pm_date', $columns)) {
                $table->date('next_pm_date')->nullable();
            }

            if (!in_array('status', $columns)) {
                $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue', 'cancelled'])
                    ->default('pending');
            }

            // أضف last_pm_date أيضاً إذا كان مطلوباً
            if (!in_array('last_pm_date', $columns)) {
                $table->date('last_pm_date')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pm_plans', function (Blueprint $table) {
            // احذف فقط الأعمدة الموجودة
            if (Schema::hasColumn('pm_plans', 'next_pm_date')) {
                $table->dropColumn('next_pm_date');
            }

            if (Schema::hasColumn('pm_plans', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};