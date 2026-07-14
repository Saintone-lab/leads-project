<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kanban_boards', function (Blueprint $table) {
            $table->json('labels')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('kanban_boards', function (Blueprint $table) {
            $table->dropColumn('labels');
        });
    }
};
