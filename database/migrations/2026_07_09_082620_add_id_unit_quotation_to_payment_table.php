<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `payment` ADD COLUMN `id_unit_quotation` BIGINT UNSIGNED NULL AFTER `id_quotation`');
        DB::statement('ALTER TABLE `payment` MODIFY COLUMN `id_quotation` BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE `payment` DROP COLUMN `id_unit_quotation`');
        DB::statement('ALTER TABLE `payment` MODIFY COLUMN `id_quotation` BIGINT UNSIGNED NOT NULL');
    }
};
