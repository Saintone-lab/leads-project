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
        Schema::table('power_service_prices', function (Blueprint $table) {
            $table->text('desc_pm1')->nullable();
            $table->text('desc_pm2')->nullable();
            $table->text('desc_pm3')->nullable();
            $table->text('desc_pm4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('power_service_prices', function (Blueprint $table) {
            $table->dropColumn(['desc_pm1', 'desc_pm2', 'desc_pm3', 'desc_pm4']);
        });
    }
};
