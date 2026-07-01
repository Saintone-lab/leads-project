<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catalog_unit_price_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalog_unit');
            $table->bigInteger('price_idr')->default(0);
            $table->decimal('price_usd', 15, 2)->default(0);
            $table->unsignedBigInteger('changed_by');
            $table->text('note')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_catalog_unit')->references('id')->on('catalog_unit')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalog_unit_price_history');
    }
};
