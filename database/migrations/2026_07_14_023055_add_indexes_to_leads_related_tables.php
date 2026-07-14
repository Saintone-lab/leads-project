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
        Schema::table('activities', function (Blueprint $table) {
            $table->index('id_client', 'idx_activities_id_client');
        });

        Schema::table('pic', function (Blueprint $table) {
            $table->index('id_client', 'idx_pic_id_client');
        });

        Schema::table('client', function (Blueprint $table) {
            $table->index('id_sales', 'idx_client_id_sales');
            $table->index('id_issues', 'idx_client_id_issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropIndex('idx_activities_id_client');
        });

        Schema::table('pic', function (Blueprint $table) {
            $table->dropIndex('idx_pic_id_client');
        });

        Schema::table('client', function (Blueprint $table) {
            $table->dropIndex('idx_client_id_sales');
            $table->dropIndex('idx_client_id_issues');
        });
    }
};
