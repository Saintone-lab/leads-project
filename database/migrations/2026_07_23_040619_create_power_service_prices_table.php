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
        Schema::create('power_service_prices', function (Blueprint $table) {
            $table->id();
            $table->string('power')->unique(); // e.g. "15 kW", "22 kW"
            $table->bigInteger('price_pm1')->default(0);
            $table->bigInteger('price_pm2')->default(0);
            $table->bigInteger('price_pm3')->default(0);
            $table->bigInteger('price_pm4')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('power_service_prices');
    }
};
