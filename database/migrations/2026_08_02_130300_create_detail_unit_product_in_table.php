<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_unit_product_in', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit_product_in')->constrained('unit_product_in')->onDelete('cascade');
            $table->foreignId('id_unit')->constrained('unit');
            $table->string('serial_number')->nullable();
            $table->decimal('harga', 15, 2)->default(0);
            $table->decimal('biaya_tambahan', 15, 2)->default(0);
            $table->string('kondisi')->nullable();
            $table->foreignId('id_unit_inventory')->nullable()->constrained('unit_inventory');
            $table->foreignId('id_fixed_asset')->nullable()->constrained('fixed_asset');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_unit_product_in');
    }
};
