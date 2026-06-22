<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pr_discussion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pending');
            $table->foreignId('id_user');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pr_discussion');
    }
};
