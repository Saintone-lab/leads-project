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
            $table->integer('doc_charged')->nullable()->after('doc_address_manual');
            $table->integer('shipping_charged')->nullable()->after('shipping_address_manual');
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
            $table->dropColumn(['doc_charged', 'shipping_charged']);
        });
    }
};
