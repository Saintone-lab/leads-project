<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchase_request', function (Blueprint $table) {
            $table->integer('qty_received')->nullable();
            $table->string('gr_status')->nullable();
            $table->text('gr_note')->nullable();
            $table->string('no_do')->nullable();
            $table->date('gr_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_request', function (Blueprint $table) {
            $table->dropColumn(['qty_received', 'gr_status', 'gr_note', 'no_do', 'gr_date']);
        });
    }
};
