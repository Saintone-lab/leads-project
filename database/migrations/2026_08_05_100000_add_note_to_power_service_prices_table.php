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
            $table->text('note_pm1')->nullable();
            $table->text('note_pm2')->nullable();
            $table->text('note_pm3')->nullable();
            $table->text('note_pm4')->nullable();
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
            $table->dropColumn(['note_pm1', 'note_pm2', 'note_pm3', 'note_pm4']);
        });
    }
};
