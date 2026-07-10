<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE `invoice` ADD COLUMN `show_spec` TINYINT(1) NOT NULL DEFAULT 1 AFTER `invoiceTo`');
    }

    public function down()
    {
        DB::statement('ALTER TABLE `invoice` DROP COLUMN `show_spec`');
    }
};
