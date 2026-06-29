<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->unsignedBigInteger('root_id')->nullable()->after('id');
            $table->unsignedTinyInteger('revision_number')->default(0)->after('root_id');
            $table->boolean('is_latest')->default(1)->after('revision_number');
        });
    }

    public function down(): void
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn(['root_id', 'revision_number', 'is_latest']);
        });
    }
};
