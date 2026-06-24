<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHistoryTimestampsToSuoTable extends Migration
{
    public function up()
    {
        Schema::table('suo', function (Blueprint $table) {
            $table->unsignedBigInteger('confirmed_by')->nullable()->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('confirmed_by');
            $table->unsignedBigInteger('approved_by')->nullable()->after('confirmed_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    public function down()
    {
        Schema::table('suo', function (Blueprint $table) {
            $table->dropColumn(['confirmed_by', 'confirmed_at', 'approved_by', 'approved_at']);
        });
    }
}
