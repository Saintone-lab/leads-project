<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order', function (Blueprint $table) {
            $table->string('category')->default('Sparepart')->after('no_po');
            $table->string('receipt_status')->default('Pending')->after('category');
        });

        Schema::table('detail_purchase_order', function (Blueprint $table) {
            $table->foreignId('id_unit')->nullable()->after('product')->constrained('unit');
        });
    }

    public function down(): void
    {
        Schema::table('detail_purchase_order', function (Blueprint $table) {
            $table->dropForeign(['id_unit']);
            $table->dropColumn('id_unit');
        });

        Schema::table('purchase_order', function (Blueprint $table) {
            $table->dropColumn(['category', 'receipt_status']);
        });
    }
};
