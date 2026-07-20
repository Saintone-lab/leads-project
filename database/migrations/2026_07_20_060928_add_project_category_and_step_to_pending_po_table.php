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
        Schema::table('pending_po', function (Blueprint $table) {
            $table->string('project_category')->nullable()->after('type');
            $table->integer('project_status_step')->default(1)->after('project_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pending_po', function (Blueprint $table) {
            $table->dropColumn(['project_category', 'project_status_step']);
        });
    }
};
