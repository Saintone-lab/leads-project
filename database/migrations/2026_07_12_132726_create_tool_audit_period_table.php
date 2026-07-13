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
        Schema::create('tool_audit_period', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->tinyInteger('semester');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['Open', 'Closed'])->default('Open');
            $table->timestamps();
            $table->unique(['tahun', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tool_audit_period');
    }
};
