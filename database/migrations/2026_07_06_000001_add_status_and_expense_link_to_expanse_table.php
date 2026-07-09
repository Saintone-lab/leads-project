<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('expanse', function (Blueprint $table) {
            $table->string('status')->nullable()->after('charged');
            $table->unsignedBigInteger('id_expense')->nullable()->after('status');
        });

        // 'charged' is enum('0','1') — must compare as string, integer 1 matches by enum
        // index instead (index 1 = '0'), silently backfilling nothing.
        DB::table('expanse')->where('charged', '1')->whereNull('id_expense')->update(['status' => 'pending']);
    }

    public function down(): void
    {
        Schema::table('expanse', function (Blueprint $table) {
            $table->dropColumn(['status', 'id_expense']);
        });
    }
};
