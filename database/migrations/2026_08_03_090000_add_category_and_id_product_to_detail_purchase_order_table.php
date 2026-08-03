<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_purchase_order', function (Blueprint $table) {
            $table->string('category')->default('Sparepart')->after('id_unit');
            $table->foreignId('id_product')->nullable()->after('category')->constrained('product');
        });
    }

    public function down(): void
    {
        Schema::table('detail_purchase_order', function (Blueprint $table) {
            $table->dropForeign(['id_product']);
            $table->dropColumn(['id_product', 'category']);
        });
    }
};
