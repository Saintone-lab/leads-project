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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('commodity');
            $table->string('description');
            $table->string('detail_desc');
            $table->string('go');
            $table->string('category');
            $table->string('dimension');
            $table->integer('first_stock');
            $table->integer('stock');
            $table->integer('weight');
            $table->string('unit');
            $table->string('note');
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
        Schema::dropIfExists('product');
    }
};
