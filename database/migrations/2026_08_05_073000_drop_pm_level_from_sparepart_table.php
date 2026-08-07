<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('sparepart', function (Blueprint $table) {
            $table->dropColumn('pm_level');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sparepart', function (Blueprint $table) {
            $table->enum('pm_level', ['PM1', 'PM2', 'PM3', 'PM4'])->default('PM1');
        });
    }
};
