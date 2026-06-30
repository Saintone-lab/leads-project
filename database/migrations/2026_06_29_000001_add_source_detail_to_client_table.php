<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('client', function (Blueprint $table) {
            $table->string('source_detail', 100)->nullable()->after('source');
        });
    }

    public function down()
    {
        Schema::table('client', function (Blueprint $table) {
            $table->dropColumn('source_detail');
        });
    }
};
