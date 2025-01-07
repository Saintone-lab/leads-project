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
            $table->string('runing')->nullable();
            $table->string('load')->nullable();
            $table->string('pressure')->nullable();
            $table->string('temp');
            $table->string('temp_out')->nullable();
            $table->string('dew')->nullable();
            $table->string('drain')->nullable();
            $table->string('cleaning')->nullable();
            $table->enum('condition', ['Running', 'Stand By', 'Off']);
            $table->enum('oil_level', ['Kurang', 'Ok'])->nullable();
            $table->longText('desc');
            $table->longText('main_desc')->nullable();
            $table->longText('main_next')->nullable();
            $table->longText('technician')->nullable();
            $table->string('picture')->nullable();
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
