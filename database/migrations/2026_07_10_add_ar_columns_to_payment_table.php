<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE `payment`
            ADD COLUMN IF NOT EXISTS `level`        TINYINT(1)   NOT NULL DEFAULT 0,
            ADD COLUMN IF NOT EXISTS `type`         VARCHAR(50)  NULL,
            ADD COLUMN IF NOT EXISTS `method`       VARCHAR(50)  NULL,
            ADD COLUMN IF NOT EXISTS `date_confirm` DATE         NULL,
            ADD COLUMN IF NOT EXISTS `due_date`     DATE         NULL,
            ADD COLUMN IF NOT EXISTS `overdue`      INT          NULL,
            ADD COLUMN IF NOT EXISTS `tempo`        INT          NULL,
            ADD COLUMN IF NOT EXISTS `date`         DATE         NULL,
            ADD COLUMN IF NOT EXISTS `cost`         BIGINT       NULL,
            ADD COLUMN IF NOT EXISTS `pph`          FLOAT        NULL
        ");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `payment`
            DROP COLUMN IF EXISTS `level`,
            DROP COLUMN IF EXISTS `type`,
            DROP COLUMN IF EXISTS `method`,
            DROP COLUMN IF EXISTS `date_confirm`,
            DROP COLUMN IF EXISTS `due_date`,
            DROP COLUMN IF EXISTS `overdue`,
            DROP COLUMN IF EXISTS `tempo`,
            DROP COLUMN IF EXISTS `date`,
            DROP COLUMN IF EXISTS `cost`,
            DROP COLUMN IF EXISTS `pph`
        ");
    }
};
