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
            $table->unsignedBigInteger('doc_recipient_id')->nullable()->after('doc_charged');
            $table->unsignedBigInteger('shipping_recipient_id')->nullable()->after('shipping_charged');
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
            $table->dropColumn(['doc_recipient_id', 'shipping_recipient_id']);
        });
    }
};
