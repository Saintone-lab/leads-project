<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_part_vendor_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_serial_product')->constrained('serial_product')->onDelete('cascade');
            $table->foreignId('id_supplier')->constrained('supplier')->onDelete('cascade');
            $table->decimal('price_usd', 10, 2);
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_part_vendor_prices');
    }
};
