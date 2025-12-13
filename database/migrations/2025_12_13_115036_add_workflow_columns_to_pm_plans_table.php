<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    Schema::table('pm_plans', function (Blueprint $table) {
        $table->foreignId('assigned_to')
              ->nullable()
              ->after('device_id')
              ->constrained('users')
              ->nullOnDelete();

        $table->enum('status', [
            'pending',
            'assigned',
            'in_progress',
            'done'
        ])->default('pending')->after('next_pm_date');
    });
}

public function down()
{
    Schema::table('pm_plans', function (Blueprint $table) {
        $table->dropColumn(['assigned_to', 'status']);
    });
}

};

