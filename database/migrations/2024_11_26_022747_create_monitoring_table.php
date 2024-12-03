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
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_machine');
            $table->foreignId('id_pic');
            $table->integer('runing');
            $table->integer('load');
            $table->integer('pressure');
            $table->integer('temp');
            $table->enum('condition', ['runing', 'off']);
            $table->enum('oil_level', ['tidak', 'ok']);
            $table->longText('desc');
            $table->integer('picture')->nullable();
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
        Schema::dropIfExists('monitoring');
    }
};
