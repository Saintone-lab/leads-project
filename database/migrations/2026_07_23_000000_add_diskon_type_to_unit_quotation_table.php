<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // diskon was decimal(5,2) (max 999.99) which only fits a percentage;
        // widen it so it can also hold a Rupiah nominal amount.
        DB::statement('ALTER TABLE unit_quotation MODIFY diskon DECIMAL(15,2) NOT NULL DEFAULT 0.00');

        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->enum('diskon_type', ['percent', 'amount'])->default('percent')->after('diskon');
        });
    }

    public function down()
    {
        Schema::table('unit_quotation', function (Blueprint $table) {
            $table->dropColumn('diskon_type');
        });
        DB::statement('ALTER TABLE unit_quotation MODIFY diskon DECIMAL(5,2) NOT NULL DEFAULT 0.00');
    }
};
