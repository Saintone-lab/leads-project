<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('unit_quotation_status_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_unit_quotation');
            $table->string('status');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('id_unit_quotation')
                  ->references('id')->on('unit_quotation')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('unit_quotation_status_history');
    }
};
