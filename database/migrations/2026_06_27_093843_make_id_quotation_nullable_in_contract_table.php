<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE contract MODIFY COLUMN id_quotation BIGINT UNSIGNED NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE contract MODIFY COLUMN id_quotation BIGINT UNSIGNED NOT NULL');
    }
};
