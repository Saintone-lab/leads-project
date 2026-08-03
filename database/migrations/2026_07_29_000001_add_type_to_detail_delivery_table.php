<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_delivery', function (Blueprint $table) {
            $table->string('type')->default('item')->after('id_delivery');
        });
    }

    public function down(): void
    {
        Schema::table('detail_delivery', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
