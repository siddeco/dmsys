<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('pm_records', function (Blueprint $table) {
        $table->string('report_file')->nullable();   // PDF / Image
        $table->longText('scan_image')->nullable();  // base64
    });
}

public function down()
{
    Schema::table('pm_records', function (Blueprint $table) {
        $table->dropColumn(['report_file', 'scan_image']);
    });
}

};
