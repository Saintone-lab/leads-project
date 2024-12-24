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
        Schema::create('monitoring_weekly', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_machine');
            $table->foreignId('id_pic');
            $table->string('vaoltage');
            $table->string('ampere');
            $table->string('idle')->nullable();
            $table->string('pm');
            $table->string('remark');
            $table->string('dew')->nullable();
            $table->string('drain')->nullable();
            $table->string('pre')->nullable();
            $table->string('after')->nullable();
            $table->longText('desc');
            $table->string('picture')->nullable();
            $table->date('date');
            $table->enum('type', ['compressor', 'dryer']);
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
        Schema::dropIfExists('monitoring_weekly');
    }
};
