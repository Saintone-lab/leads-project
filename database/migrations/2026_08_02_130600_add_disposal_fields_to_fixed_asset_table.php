<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->boolean('is_disposed')->default(false)->after('status_unit');
            $table->date('tanggal_disposal')->nullable()->after('is_disposed');
            $table->decimal('nilai_buku_disposal', 15, 2)->nullable()->after('tanggal_disposal');
            $table->decimal('harga_jual_final', 15, 2)->nullable()->after('nilai_buku_disposal');
        });
    }

    public function down(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->dropColumn(['is_disposed', 'tanggal_disposal', 'nilai_buku_disposal', 'harga_jual_final']);
        });
    }
};
