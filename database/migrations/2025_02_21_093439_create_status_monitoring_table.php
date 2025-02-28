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
        Schema::create('status_monitoring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_monitoring');
            $table->foreignId('id_pic');
            $table->enum('status',[0,1,2,3,4]);
            $table->longText('desc');
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
        Schema::dropIfExists('status_monitoring');
    }
};
