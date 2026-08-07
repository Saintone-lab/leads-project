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
        Schema::dropIfExists('sparepart');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sparepart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_unit')->nullable();
            $table->unsignedBigInteger('id_equivalent')->nullable();
            $table->integer('qty')->nullable();
            $table->string('qty_info')->nullable();
            $table->timestamps();
        });
    }
};
