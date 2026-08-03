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
        Schema::table('machine', function (Blueprint $table) {
            $table->enum('visit_1_type', ['PM1', 'PM2'])->nullable();
            $table->date('visit_1_date')->nullable();
            
            $table->enum('visit_2_type', ['PM1', 'PM2'])->nullable();
            $table->date('visit_2_date')->nullable();
            
            $table->enum('visit_3_type', ['PM1', 'PM2'])->nullable();
            $table->date('visit_3_date')->nullable();
            
            $table->enum('visit_4_type', ['PM1', 'PM2'])->nullable();
            $table->date('visit_4_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machine', function (Blueprint $table) {
            $table->dropColumn([
                'visit_1_type', 'visit_1_date',
                'visit_2_type', 'visit_2_date',
                'visit_3_type', 'visit_3_date',
                'visit_4_type', 'visit_4_date'
            ]);
        });
    }
};
