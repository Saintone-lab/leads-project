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
        Schema::table('machine', function (Blueprint $table) {
            // Check if column running exists, otherwise add at the end
            $table->boolean('is_forecasted')->default(true);
            $table->enum('forecast_type', ['parts', 'regular_service', 'contract'])->default('regular_service');
            $table->date('last_service_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machine', function (Blueprint $table) {
            $table->dropColumn(['is_forecasted', 'forecast_type', 'last_service_date']);
        });
    }
};
