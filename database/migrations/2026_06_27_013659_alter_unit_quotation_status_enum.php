<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE unit_quotation MODIFY COLUMN status ENUM('draft','sent','negotiation','revision','hot_prospect','po_received','loss') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE unit_quotation MODIFY COLUMN status ENUM('draft','sent','approved','rejected') NOT NULL DEFAULT 'draft'");
    }
};
