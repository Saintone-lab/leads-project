<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->string('jenis_kendaraan')->nullable()->after('harga_jual');
            $table->string('merk_model')->nullable()->after('jenis_kendaraan');
            $table->string('bahan_bakar')->nullable()->after('merk_model');
            $table->string('plat_nomor')->nullable()->after('bahan_bakar');
            $table->string('atas_nama')->nullable()->after('plat_nomor');
        });
    }

    public function down(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->dropColumn(['jenis_kendaraan', 'merk_model', 'bahan_bakar', 'plat_nomor', 'atas_nama']);
        });
    }
};
