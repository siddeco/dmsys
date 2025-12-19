<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pm_plans', function (Blueprint $table) {
            // assigned_to
            if (!Schema::hasColumn('pm_plans', 'assigned_to')) {
                $table->foreignId('assigned_to')
                    ->nullable()
                    ->after('device_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            // status
            if (!Schema::hasColumn('pm_plans', 'status')) {
                $table->enum('status', [
                    'scheduled',
                    'assigned',
                    'in_progress',
                    'completed',
                    'overdue',
                ])->default('scheduled')->after('assigned_to');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pm_plans', function (Blueprint $table) {
            if (Schema::hasColumn('pm_plans', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('pm_plans', 'assigned_to')) {
                $table->dropConstrainedForeignId('assigned_to');
            }
        });
    }
};
