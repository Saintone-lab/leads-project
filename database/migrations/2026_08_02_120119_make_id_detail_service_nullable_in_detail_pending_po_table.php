<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make id_detail_service nullable in detail_pending_po table.
     * This column is only required for Service Quotation flows.
     * Unit Quotation and Sparepart Quotation do not use this field,
     * so it must allow NULL to avoid SQLSTATE[HY000]: 1364 errors.
     */
    public function up(): void
    {
        // Using raw SQL because doctrine/dbal is not installed in this project.
        // ->change() requires it, but ALTER TABLE works directly.
        DB::statement('ALTER TABLE detail_pending_po MODIFY id_detail_service BIGINT UNSIGNED NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE detail_pending_po MODIFY id_detail_service BIGINT UNSIGNED NOT NULL');
    }
};
