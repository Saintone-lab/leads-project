<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_request', function (Blueprint $table) {
            $table->unsignedBigInteger('id_user')->nullable()->after('id_pending');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_request', function (Blueprint $table) {
            $table->dropColumn('id_user');
        });
    }
};
