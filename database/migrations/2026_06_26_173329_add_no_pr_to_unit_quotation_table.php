<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->string('no_pr')->nullable()->after('attn');
        });
    }

    public function down()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn('no_pr');
        });
    }
};
