<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->string('status_unit')->nullable()->after('qc_status');
        });
    }

    public function down(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->dropColumn('status_unit');
        });
    }
};
