<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->unsignedInteger('alert_threshold')
                ->default(5)
                ->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('spare_parts', function (Blueprint $table) {
            $table->dropColumn('alert_threshold');
        });
    }

};
