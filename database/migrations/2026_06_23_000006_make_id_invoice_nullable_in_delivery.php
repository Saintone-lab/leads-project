<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MakeIdInvoiceNullableInDelivery extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE `delivery` MODIFY `id_invoice` BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE `delivery` MODIFY `id_invoice` BIGINT UNSIGNED NOT NULL');
    }
}
