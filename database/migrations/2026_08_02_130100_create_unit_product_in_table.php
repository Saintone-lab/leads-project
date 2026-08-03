<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_product_in', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->enum('transaction_type', ['purchase_new', 'purchase_used', 'trade_in']);
            $table->foreignId('id_po')->nullable()->constrained('purchase_order');
            $table->foreignId('id_supplier')->nullable()->constrained('supplier');
            $table->string('id_customer')->nullable();
            $table->date('date');
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_product_in');
    }
};
