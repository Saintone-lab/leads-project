<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->string('type')->nullable()->after('attn');
            $table->string('week')->nullable()->after('type');
            $table->string('validity')->nullable()->after('note');
            $table->string('pricing')->nullable()->after('validity');
            $table->string('delivery_process')->nullable()->after('pricing');
            $table->string('payment')->nullable()->after('delivery_process');
        });
    }

    public function down()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn(['type', 'week', 'validity', 'pricing', 'delivery_process', 'payment']);
        });
    }
};
