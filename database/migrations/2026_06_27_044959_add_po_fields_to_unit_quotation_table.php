<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->string('po_number')->nullable()->after('status');
            $table->string('po_file')->nullable()->after('po_number');
        });
    }

    public function down(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn(['po_number', 'po_file']);
        });
    }
};
