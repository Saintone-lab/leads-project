<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('unit_quotation_detail', function (Blueprint $table) {
            $table->decimal('pph', 5, 2)->default(0)->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('unit_quotation_detail', function (Blueprint $table) {
            $table->dropColumn('pph');
        });
    }
};
