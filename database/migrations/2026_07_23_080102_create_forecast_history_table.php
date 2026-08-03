<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forecast_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_machine');
            $table->integer('year');
            $table->string('forecast_type')->default('parts');
            $table->boolean('is_forecasted')->default(true);
            $table->string('visit_1_type')->nullable();
            $table->date('visit_1_date')->nullable();
            $table->string('visit_2_type')->nullable();
            $table->date('visit_2_date')->nullable();
            $table->string('visit_3_type')->nullable();
            $table->date('visit_3_date')->nullable();
            $table->string('visit_4_type')->nullable();
            $table->date('visit_4_date')->nullable();
            $table->timestamps();

            // Foreign key to machines table
            $table->foreign('id_machine')->references('id')->on('machine')->onDelete('cascade');
            
            // Unique constraint: one machine can only have one forecast plan per year
            $table->unique(['id_machine', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecast_history');
    }
};
