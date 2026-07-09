<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->unsignedBigInteger('id_unit')->nullable()->after('id_supplier');
            $table->string('qc_status')->nullable()->after('status');
            $table->unsignedBigInteger('confirmed_by')->nullable()->after('qc_status');
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');
            $table->date('mulai_penyusutan')->nullable()->after('pakai');
        });
    }

    public function down(): void
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->dropColumn(['id_unit', 'qc_status', 'confirmed_by', 'confirmed_at', 'mulai_penyusutan']);
        });
    }
};
