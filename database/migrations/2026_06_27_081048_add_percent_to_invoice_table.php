<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE invoice ADD COLUMN percent DECIMAL(5,2) NULL DEFAULT NULL AFTER `type`');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE invoice DROP COLUMN percent');
    }
};
