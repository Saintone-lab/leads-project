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
        // Table is empty (just created), drop and recreate with clean structure
        Schema::dropIfExists('sales_target_histories');
        Schema::create('sales_target_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedSmallInteger('year');
            $table->bigInteger('target_annual');
            $table->foreignId('set_by')->constrained('users');
            $table->timestamps();

            $table->unique(['user_id', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_target_histories');
        Schema::create('sales_target_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->bigInteger('target_amount');
            $table->foreignId('set_by')->constrained('users');
            $table->timestamps();

            $table->unique(['user_id', 'year', 'month']);
        });
    }
};
