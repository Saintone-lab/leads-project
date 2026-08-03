<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pending_po', function (Blueprint $table) {
            if (!Schema::hasColumn('pending_po', 'id_unit_quotation')) {
                $table->unsignedBigInteger('id_unit_quotation')->nullable()->after('id_quotation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pending_po', function (Blueprint $table) {
            if (Schema::hasColumn('pending_po', 'id_unit_quotation')) {
                $table->dropColumn('id_unit_quotation');
            }
        });
    }
};
