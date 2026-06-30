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
        Schema::table('sales_reports', function (Blueprint $table) {
            $table->bigInteger('target')->nullable()->default(null)->after('year');
        });
    }

    public function down()
    {
        Schema::table('sales_reports', function (Blueprint $table) {
            $table->dropColumn('target');
        });
    }
};
