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
        Schema::table('crm_status', function (Blueprint $table) {
            $table->index('id_client', 'idx_crm_status_id_client');
            $table->index('status', 'idx_crm_status_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_status', function (Blueprint $table) {
            $table->dropIndex('idx_crm_status_id_client');
            $table->dropIndex('idx_crm_status_status');
        });
    }
};
