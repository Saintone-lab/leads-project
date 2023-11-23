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
        Schema::create('detail_quotation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_quotation');
            $table->string('product', 25); // detail
            $table->integer('qty'); // detail
            $table->integer('price'); // detail
            $table->integer('amount'); // detail
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
        Schema::dropIfExists('detail_quotation');
    }
};
