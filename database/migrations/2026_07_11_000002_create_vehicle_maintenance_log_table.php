<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_maintenance_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_fixed_asset');
            $table->string('jenis');
            $table->date('tanggal');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->decimal('biaya', 15, 2)->nullable();
            $table->string('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_maintenance_log');
    }
};
