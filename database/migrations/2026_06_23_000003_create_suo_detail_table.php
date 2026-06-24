<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suo_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_suo')->constrained('suo')->onDelete('cascade');
            $table->string('item_name');
            $table->integer('qty');
            $table->string('unit')->nullable();
            $table->enum('stock_status', ['ready', 'not_ready'])->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suo_detail');
    }
};
