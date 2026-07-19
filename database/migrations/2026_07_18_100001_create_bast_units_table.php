<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bast_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bast')->index();
            $table->string('unit_name');
            $table->string('serial_no')->nullable();
            $table->integer('qty')->default(1);
            $table->integer('position')->default(0);
            $table->timestamps();

            $table->foreign('id_bast')->references('id')->on('basts')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bast_units');
    }
};
