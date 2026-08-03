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
            $table->string('doc_address_type')->default('customer')->after('charged');
            $table->text('doc_address_manual')->nullable()->after('doc_address_type');
            $table->string('shipping_address_type')->default('customer')->after('doc_address_manual');
            $table->text('shipping_address_manual')->nullable()->after('shipping_address_type');
            $table->boolean('combine_shipping_and_parts')->default(true)->after('shipping_address_manual');
        });

        Schema::table('expanse', function (Blueprint $table) {
            $table->string('description')->nullable()->after('cost');
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
            $table->dropColumn([
                'doc_address_type',
                'doc_address_manual',
                'shipping_address_type',
                'shipping_address_manual',
                'combine_shipping_and_parts'
            ]);
        });

        Schema::table('expanse', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
