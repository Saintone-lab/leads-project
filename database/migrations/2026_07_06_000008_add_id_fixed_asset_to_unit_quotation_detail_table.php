<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_quotation_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('id_fixed_asset')->nullable()->after('id_unit');
        });
    }

    public function down(): void
    {
        Schema::table('unit_quotation_detail', function (Blueprint $table) {
            $table->dropColumn('id_fixed_asset');
        });
    }
};
