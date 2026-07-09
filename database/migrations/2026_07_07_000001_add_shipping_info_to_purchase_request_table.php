<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_request', function (Blueprint $table) {
            $table->string('purchase_type')->nullable()->after('status');
            $table->string('cargo')->nullable()->after('purchase_type');
            $table->string('no_resi')->nullable()->after('cargo');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_request', function (Blueprint $table) {
            $table->dropColumn(['purchase_type', 'cargo', 'no_resi']);
        });
    }
};
