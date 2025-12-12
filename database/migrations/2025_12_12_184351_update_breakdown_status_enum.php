<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE breakdowns 
            MODIFY status ENUM(
                'open',
                'assigned',
                'in_progress',
                'resolved',
                'closed'
            ) NOT NULL DEFAULT 'open'
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE breakdowns 
            MODIFY status ENUM(
                'open',
                'in_progress',
                'resolved'
            ) NOT NULL DEFAULT 'open'
        ");
    }
};
