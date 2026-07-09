<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_asset_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_fixed_asset');
            $table->unsignedBigInteger('id_detail_product');
            $table->string('warehouse');
            $table->integer('qty');
            $table->decimal('price', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->string('note')->nullable();
            $table->date('date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_asset_service');
    }
};
