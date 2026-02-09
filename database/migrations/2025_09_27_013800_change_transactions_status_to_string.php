<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeTransactionsStatusToString extends Migration
{
    public function up()
    {
        // change status from integer to varchar so it can store 'pending'/'approved'
        DB::statement("
            ALTER TABLE `transactions`
            CHANGE `status` `status` VARCHAR(50) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down()
    {
        // revert back to tinyint (0 = pending, 1 = approved)
        DB::statement("
            ALTER TABLE `transactions`
            CHANGE `status` `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0
        ");
    }
}
