<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `invoice` MODIFY COLUMN `type` VARCHAR(50) NULL DEFAULT 'CT'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `invoice` MODIFY COLUMN `type` ENUM('CT', 'DP', 'BP') NULL DEFAULT 'CT'");
    }
};
