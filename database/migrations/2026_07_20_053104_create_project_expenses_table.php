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
        Schema::create('project_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pending');
            $table->unsignedBigInteger('id_user');
            $table->string('name');
            $table->string('category');
            $table->bigInteger('amount');
            $table->date('date');
            $table->string('receipt')->nullable();
            $table->timestamps();

            $table->foreign('id_pending')->references('id')->on('pending_po')->onDelete('cascade');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_expenses');
    }
};
