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
            $table->foreignId('id_pic');
            $table->foreignId('id_sales');
            $table->foreignId('id_service')->nullable();
            $table->string('no_pr')->nullable();
            $table->string('title');
            $table->integer('status');
            $table->string('note');
            $table->date('estimated_date');
            $table->date('expired_date');
            $table->date('po_date')->nullable();
            $table->integer('tax');
            $table->integer('shipping');
            $table->string('no_quote');
            $table->integer('diskon');
            $table->integer('subtotal');
            $table->integer('harga_total');
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
