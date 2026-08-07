<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE unit_pm_template_items MODIFY COLUMN type ENUM('part', 'custom', 'header') NOT NULL DEFAULT 'part'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE unit_pm_template_items MODIFY COLUMN type ENUM('part', 'custom') NOT NULL DEFAULT 'part'");
    }
};
