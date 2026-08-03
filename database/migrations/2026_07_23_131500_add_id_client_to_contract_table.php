<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contract', function (Blueprint $table) {
            $table->unsignedBigInteger('id_client')->nullable()->after('id_unit_quotation');
            $table->foreign('id_client')->references('id')->on('client')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract', function (Blueprint $table) {
            $table->dropForeign(['id_client']);
            $table->dropColumn('id_client');
        });
    }
};
