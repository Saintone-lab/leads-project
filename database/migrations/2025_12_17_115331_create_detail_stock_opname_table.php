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
        Schema::create('detail_stock_opname', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_stock_opname');
            $table->foreignId('id_product');
            $table->foreignId('stock_sistem');
            $table->foreignId('stock_gudang');
            $table->foreignId('selisih');
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
        Schema::dropIfExists('detail_stock_opname');
    }
};
