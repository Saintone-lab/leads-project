<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('suo', 'id_unit_quotation')) {
            Schema::table('suo', function (Blueprint $table) {
                $table->foreignId('id_unit_quotation')->nullable()->after('id_quotation')->constrained('unit_quotation')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('suo', 'id_unit_quotation')) {
            Schema::table('suo', function (Blueprint $table) {
                $table->dropForeign(['id_unit_quotation']);
                $table->dropColumn('id_unit_quotation');
            });
        }
    }
};
