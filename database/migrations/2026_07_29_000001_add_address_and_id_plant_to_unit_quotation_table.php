<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->unsignedBigInteger('id_plant')->nullable()->after('id_pic');
            $table->text('address')->nullable()->after('attn');

            $table->foreign('id_plant')
                  ->references('id')
                  ->on('client_plants')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropForeign(['id_plant']);
            $table->dropColumn(['id_plant', 'address']);
        });
    }
};
