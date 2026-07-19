<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->foreignId('id_support')->nullable()->after('id_sales');
        });
    }

    public function down(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn('id_support');
        });
    }
};
