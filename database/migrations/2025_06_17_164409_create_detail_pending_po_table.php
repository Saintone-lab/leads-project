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
        Schema::create('detail_pending_po', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pending');
            $table->foreignId('id_replacement');
            $table->integer('qty');
            $table->string('status');
            $table->string('desc');
            $table->longText('note');
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
        Schema::dropIfExists('detail_pending_po');
    }
};
