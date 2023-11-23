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
        Schema::create('quotation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_client');
            $table->foreignId('id_sales');
            $table->foreignId('id_service')->nullable();
            $table->string('status', 15);
            $table->date('expired_date');
            $table->integer('disc');
            $table->integer('tax');
            $table->integer('shipping');
            $table->integer('subtotal');
            $table->integer('harga total');
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
        Schema::dropIfExists('quotation');
    }
};
