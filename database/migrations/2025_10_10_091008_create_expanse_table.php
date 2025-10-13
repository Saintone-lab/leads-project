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
        Schema::create('expanse', function (Blueprint $table) {
            $table->id();
            $table->string('kurir');
            $table->string('no_track');
            $table->string('image');
            $table->string('type');
            $table->enum('charged', [0,1]);
            $table->integer('cost');
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
        Schema::dropIfExists('expanse');
    }
};
