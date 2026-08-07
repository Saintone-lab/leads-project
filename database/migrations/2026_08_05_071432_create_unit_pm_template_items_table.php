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
        Schema::create('unit_pm_template_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_unit');
            $table->enum('level', ['PM1', 'PM2', 'PM3', 'PM4']);
            $table->enum('type', ['part', 'custom'])->default('part');
            $table->unsignedBigInteger('id_equivalent')->nullable();
            $table->string('label');
            $table->text('description')->nullable();
            $table->decimal('qty', 15, 2)->default(1);
            $table->string('info_qty')->default('Pcs');
            $table->decimal('price', 15, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['id_unit', 'level']);
            $table->foreign('id_unit')->references('id')->on('unit')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('unit_pm_template_items');
    }
};
