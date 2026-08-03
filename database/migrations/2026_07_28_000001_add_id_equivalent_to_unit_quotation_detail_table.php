<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_quotation_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('id_equivalent')->nullable()->after('id_fixed_asset');
        });
    }

    public function down(): void
    {
        Schema::table('unit_quotation_detail', function (Blueprint $table) {
            $table->dropColumn('id_equivalent');
        });
    }
};
