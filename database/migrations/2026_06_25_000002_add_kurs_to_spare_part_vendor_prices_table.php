<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spare_part_vendor_prices', function (Blueprint $table) {
            $table->decimal('kurs_usd', 10, 2)->default(0)->after('price_usd');
            $table->decimal('price_idr', 15, 2)->default(0)->after('kurs_usd');
        });
    }

    public function down(): void
    {
        Schema::table('spare_part_vendor_prices', function (Blueprint $table) {
            $table->dropColumn(['kurs_usd', 'price_idr']);
        });
    }
};
