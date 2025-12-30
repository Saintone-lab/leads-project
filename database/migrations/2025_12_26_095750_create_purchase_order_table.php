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
        Schema::create('purchase_order', function (Blueprint $table) {
            $table->id();
            $table->string('no_po');
            $table->string('attn');
            $table->string('mobile');
            $table->string('company');
            $table->string('email');
            $table->string('address');
            $table->string('phone');
            $table->string('payment');
            $table->string('delivery');
            $table->longText('note');
            $table->integer('subtotal');
            $table->integer('vat');
            $table->integer('total');
            $table->date('date');
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
        Schema::dropIfExists('purchase_order');
    }
};
