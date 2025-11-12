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
        Schema::create('service_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sales_order');
            $table->enum('SJ', [0,1]);
            $table->enum('BA', [0,1]);
            $table->longText('note_doc');
            $table->longText('note_schedule');
            $table->date('date_schedule');
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
        Schema::dropIfExists('service_order');
    }
};
