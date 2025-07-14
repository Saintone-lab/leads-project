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
        Schema::create('sales_online', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sales');
            $table->integer('airend')->nullable();
            $table->integer('kojisha')->nullable();
            $table->integer('average')->nullable();
            $table->longText('product')->nullable();
            $table->longText('ig')->nullable();
            $table->longText('tiktok')->nullable();
            $table->longText('tokped')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('sales_online');
    }
};
