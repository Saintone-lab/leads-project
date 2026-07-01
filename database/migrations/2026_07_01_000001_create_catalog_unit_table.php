<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_unit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_unit')->unique();
            $table->bigInteger('price_idr')->default(0);
            $table->decimal('price_usd', 15, 2)->default(0);
            $table->text('spec_note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id_unit')->references('id')->on('unit')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_unit');
    }
};
