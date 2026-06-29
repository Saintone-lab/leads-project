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
    public function up()
    {
        Schema::table('unit', function (Blueprint $table) {
            if (!Schema::hasColumn('unit', 'filtration')) {
                $table->string('filtration')->nullable()->after('pdp');
            }
            if (!Schema::hasColumn('unit', 'oil_content')) {
                $table->string('oil_content')->nullable()->after('filtration');
            }
            if (!Schema::hasColumn('unit', 'grade')) {
                $table->string('grade')->nullable()->after('oil_content');
            }
        });
    }

    public function down()
    {
        Schema::table('unit', function (Blueprint $table) {
            $table->dropColumn(['filtration', 'oil_content', 'grade']);
        });
    }
};
