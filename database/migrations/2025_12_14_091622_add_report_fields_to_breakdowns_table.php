<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {

            // مسار ملف تقرير الصيانة (PDF / Image / Scan)
            $table->string('engineer_report')->nullable()->after('description');

            // وقت إغلاق البلاغ
            $table->timestamp('completed_at')->nullable()->after('engineer_report');

        });
    }

    public function down(): void
    {
        Schema::table('breakdowns', function (Blueprint $table) {
            $table->dropColumn([
                'engineer_report',
                'completed_at',
            ]);
        });
    }
};
