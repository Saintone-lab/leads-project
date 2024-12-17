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
            $table->integer('runing')->nullable();
            $table->integer('pressure')->nullable();
            $table->integer('temp');
            $table->integer('temp_out')->nullable();
            $table->integer('dew')->nullable();
            $table->integer('drain')->nullable();
            $table->integer('cleaning')->nullable();
            $table->enum('condition', ['Running', 'Stand By', 'Off']);
            $table->enum('oil_level', ['Kurang', 'Ok'])->nullable();
            $table->longText('desc');
            $table->integer('picture')->nullable();
            $table->date('date');
            $table->enum('type', ['compressor', 'dryer']);
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
