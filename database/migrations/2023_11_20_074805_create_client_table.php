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
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->foreignId("id_sales");
            $table->foreignId("id_pic");
            $table->foreignId("id_issues");
            $table->string('company', 50);
            $table->string('email', 25);
            $table->string('phone', 15);
            $table->string('web')->nullable();
            $table->string('image')->nullable();
            $table->string('source', 15);
            $table->date("created_date");
            $table->enum('role', ['Leads', 'Customers'])->default("Leads");
            $table->string("mobile", 14);
            $table->string("address");
            $table->string("area", 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()  
    {
        Schema::dropIfExists('client');
    }
};
