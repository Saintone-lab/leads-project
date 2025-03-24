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
        Schema::create('main_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_issue')->nulllable();
            $table->foreignId('id_machine');
            $table->foreignId('id_teknisi');
            $table->longText('desc');
            $table->longText('next');
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
        Schema::dropIfExists('main_log');
    }
};
