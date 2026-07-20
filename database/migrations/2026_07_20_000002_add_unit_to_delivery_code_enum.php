<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE delivery MODIFY code ENUM('Sparepart','Service','Manual','Overhaul','Unit') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE delivery MODIFY code ENUM('Sparepart','Service','Manual','Overhaul') NOT NULL");
    }
};
