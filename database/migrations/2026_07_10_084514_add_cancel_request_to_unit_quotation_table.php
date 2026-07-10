<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->tinyInteger('cancel_request')->default(0)->after('status');
        });
    }

    public function down()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn('cancel_request');
        });
    }
};
