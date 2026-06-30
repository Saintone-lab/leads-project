<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE invoice ADD COLUMN IF NOT EXISTS id_unit_quotation BIGINT UNSIGNED NULL DEFAULT NULL AFTER id_quotation');
    }

    public function down()
    {
        DB::statement('ALTER TABLE invoice DROP COLUMN IF EXISTS id_unit_quotation');
    }
};
