<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_sales_mapping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_accounting');
            $table->unsignedBigInteger('id_sales');
            $table->timestamps();

            $table->unique(['id_accounting', 'id_sales']);
            $table->index('id_sales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounting_sales_mapping');
    }
};
