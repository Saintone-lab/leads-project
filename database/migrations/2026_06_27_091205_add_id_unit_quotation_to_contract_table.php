<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE contract ADD COLUMN id_unit_quotation INT NULL DEFAULT NULL AFTER id_quotation');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE contract DROP COLUMN id_unit_quotation');
    }
};
