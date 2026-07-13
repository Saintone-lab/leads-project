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
        Schema::create('tool_master', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tools');
            $table->string('kategori')->nullable();
            $table->string('spesifikasi')->nullable();
            $table->string('foto_referensi')->nullable();
            $table->string('link_pembelian')->nullable();
            $table->decimal('harga_referensi', 15, 2)->nullable();
            $table->boolean('status_aktif')->default(1);
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
        Schema::dropIfExists('tool_master');
    }
};
