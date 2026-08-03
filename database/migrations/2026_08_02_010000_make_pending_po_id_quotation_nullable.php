<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pending_po') && Schema::hasColumn('pending_po', 'id_quotation')) {
            DB::statement('ALTER TABLE pending_po MODIFY id_quotation BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pending_po') && Schema::hasColumn('pending_po', 'id_quotation')) {
            DB::statement('ALTER TABLE pending_po MODIFY id_quotation BIGINT UNSIGNED NOT NULL');
        }
    }
};
