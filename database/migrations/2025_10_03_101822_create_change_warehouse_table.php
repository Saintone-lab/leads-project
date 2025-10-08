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
        Schema::create('change_warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sender');
            $table->foreignId('id_reciever');
            $table->enum('from', ['BDG', 'BKS'])->nullable();
            $table->enum('to', ['BDG', 'BKS'])->nullable();
            $table->string('kurir')->nullable();
            $table->longText('note')->nullable();
            $table->longText('note_recieve')->nullable();
            $table->integer('status')->nullable();
            $table->date('date')->nullable();
            $table->date('date_recieve')->nullable();
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
        Schema::dropIfExists('change_warehouse');
    }
};
