<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_in', function (Blueprint $table) {
            $table->string('no_product_in')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('product_in', function (Blueprint $table) {
            $table->dropColumn('no_product_in');
        });
    }
};
