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
        Schema::create('sales_payment_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sales');
            $table->unsignedBigInteger('id_client')->nullable();
            $table->string('name');
            $table->text('payment_term');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->foreign('id_sales')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_client')->references('id')->on('client')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_payment_templates');
    }
};
