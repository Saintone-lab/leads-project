<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pr_discussion_mention', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_discussion');
            $table->foreignId('id_user_mention');
            $table->enum('level', ['0', '1'])->default('0'); // 0=belum baca, 1=sudah baca
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pr_discussion_mention');
    }
};
