<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('breakdowns', function (Blueprint $table) {

        // تحديث ENUM status فقط
        $table->enum('status', [
            'open',
            'assigned',
            'in_progress',
            'resolved',
            'closed'
        ])->default('open')->change();

        // أضف فقط الأعمدة غير الموجودة
        if (!Schema::hasColumn('breakdowns', 'started_at')) {
            $table->timestamp('started_at')->nullable()->after('assigned_at');
        }

        if (!Schema::hasColumn('breakdowns', 'resolved_at')) {
            $table->timestamp('resolved_at')->nullable()->after('started_at');
        }

        if (!Schema::hasColumn('breakdowns', 'closed_at')) {
            $table->timestamp('closed_at')->nullable()->after('resolved_at');
        }

        if (!Schema::hasColumn('breakdowns', 'resolution_notes')) {
            $table->text('resolution_notes')->nullable()->after('closed_at');
        }
    });
}


    public function down(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            $table->dropColumn([
                'assigned_at',
                'started_at',
                'resolved_at',
                'closed_at',
                'resolution_notes'
            ]);
        });
    }
};
