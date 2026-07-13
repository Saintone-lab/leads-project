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
        Schema::create('tool_audit_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tool_audit');
            $table->unsignedBigInteger('id_fixed_asset');
            $table->integer('qty_actual')->nullable();
            $table->enum('kondisi', ['Ada', 'Rusak', 'Hilang'])->nullable();
            $table->string('foto_audit')->nullable();
            $table->string('alasan')->nullable();
            $table->enum('metode_ganti', ['Beli Sendiri', 'Potong Bonus'])->nullable();
            $table->enum('status_verifikasi_item', ['Pending', 'Sesuai', 'Tidak Sesuai'])->default('Pending');
            $table->string('catatan_admin_item')->nullable();
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
        Schema::dropIfExists('tool_audit_item');
    }
};
