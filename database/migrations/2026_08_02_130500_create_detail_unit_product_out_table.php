<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_unit_product_out', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit_product_out')->constrained('unit_product_out')->onDelete('cascade');
            $table->enum('source_type', ['unit_inventory', 'fixed_asset']);
            $table->foreignId('id_unit_inventory')->nullable()->constrained('unit_inventory');
            $table->foreignId('id_fixed_asset')->nullable()->constrained('fixed_asset');
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->decimal('nilai_pokok', 15, 2)->default(0);
            $table->decimal('selisih', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_unit_product_out');
    }
};
