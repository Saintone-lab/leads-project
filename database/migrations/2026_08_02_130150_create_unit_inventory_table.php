<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('unit');
            $table->string('serial_number')->nullable();
            $table->decimal('harga_modal', 15, 2)->default(0);
            $table->decimal('biaya_rebranding', 15, 2)->default(0);
            $table->decimal('total_modal', 15, 2)->default(0);
            $table->enum('status', ['available', 'sold'])->default('available');
            $table->foreignId('id_unit_product_in')->nullable()->constrained('unit_product_in');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_inventory');
    }
};
