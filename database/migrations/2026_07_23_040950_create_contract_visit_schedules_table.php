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
        Schema::create('contract_visit_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contract');
            $table->integer('visit_number');
            $table->date('planned_date');
            $table->bigInteger('estimated_revenue');
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('id_contract')->references('id')->on('contract')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_visit_schedule');
    }
};
