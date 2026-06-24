<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suo', function (Blueprint $table) {
            $table->id();
            $table->string('no_suo')->unique();
            $table->string('company');
            $table->string('pic');
            $table->text('address');
            $table->text('notes')->nullable();
            $table->foreignId('id_sales')->constrained('users');
            $table->enum('status', ['draft', 'submitted', 'confirmed', 'goods_out', 'converted'])->default('draft');
            $table->string('no_invoice_booking')->nullable();
            $table->foreignId('id_quotation')->nullable()->constrained('quotation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suo');
    }
};
