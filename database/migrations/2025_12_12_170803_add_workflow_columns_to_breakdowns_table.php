<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {

            // المستخدم الذي أنشأ البلاغ
            if (!Schema::hasColumn('breakdowns', 'reported_by')) {
                $table->foreignId('reported_by')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

            // المستخدم المعيّن (فني / مهندس)
            if (!Schema::hasColumn('breakdowns', 'assigned_to')) {
                $table->foreignId('assigned_to')
                      ->nullable()
                      ->constrained('users')
                      ->nullOnDelete();
            }

            // أوقات الـ workflow
            if (!Schema::hasColumn('breakdowns', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable();
            }

            if (!Schema::hasColumn('breakdowns', 'closed_at')) {
                $table->timestamp('closed_at')->nullable();
            }

            // تقرير الإغلاق
            if (!Schema::hasColumn('breakdowns', 'resolution_notes')) {
                $table->text('resolution_notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {

            if (Schema::hasColumn('breakdowns', 'reported_by')) {
                $table->dropForeign(['reported_by']);
                $table->dropColumn('reported_by');
            }

            if (Schema::hasColumn('breakdowns', 'assigned_to')) {
                $table->dropForeign(['assigned_to']);
                $table->dropColumn('assigned_to');
            }

            $table->dropColumn([
                'assigned_at',
                'closed_at',
                'resolution_notes'
            ]);
        });
    }
};
