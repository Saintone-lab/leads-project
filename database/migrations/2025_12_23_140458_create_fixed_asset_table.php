<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_asset', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_aktiva');
            $table->foreignId('id_penyusutan');
            $table->foreignId('id_beban');
            $table->foreignId('id_supplier');
            $table->foreignId('id_pengeluaran');
            $table->string('type');
            $table->string('code');
            $table->string('no_invoice');
            $table->date('beli');
            $table->date('pakai');
            $table->date('bayar');
            $table->string('metode');
            $table->string('desc');
            $table->integer('umur');
            $table->integer('qty');
            $table->integer('status')->default(0);
            $table->integer('total');
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
        Schema::dropIfExists('fixed_asset');
    }
};
