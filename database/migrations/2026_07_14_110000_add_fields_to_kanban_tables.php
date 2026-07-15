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
        Schema::table('kanban_boards', function (Blueprint $table) {
            $table->string('type')->default('dynamic')->after('description');
            $table->string('notification_sound')->nullable()->after('type');
        });

        Schema::table('kanban_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('pending_po_id')->nullable()->after('column_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kanban_tasks', function (Blueprint $table) {
            $table->dropColumn('pending_po_id');
        });

        Schema::table('kanban_boards', function (Blueprint $table) {
            $table->dropColumn(['type', 'notification_sound']);
        });
    }
};
