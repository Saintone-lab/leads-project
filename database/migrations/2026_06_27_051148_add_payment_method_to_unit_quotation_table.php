<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('po_file');
        });
    }

    public function down(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
