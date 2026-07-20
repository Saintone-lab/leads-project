<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('client_plants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_client')->index();
            $table->string('name');
            $table->text('address')->nullable();
            $table->timestamps();

            $table->foreign('id_client')->references('id')->on('client')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_plants');
    }
};
