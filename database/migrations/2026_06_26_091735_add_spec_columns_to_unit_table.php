<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unit', function (Blueprint $table) {
            if (!Schema::hasColumn('unit', 'brand')) {
                $table->string('brand')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('unit', 'model')) {
                $table->string('model')->nullable()->after('brand');
            }
            if (!Schema::hasColumn('unit', 'type_unit')) {
                $table->string('type_unit')->nullable()->after('model');
            }
            if (!Schema::hasColumn('unit', 'cooling')) {
                $table->string('cooling')->nullable()->after('connect');
            }
            if (!Schema::hasColumn('unit', 'exhaust')) {
                $table->string('exhaust')->nullable()->after('cooling');
            }
            if (!Schema::hasColumn('unit', 'refrigerant_type')) {
                $table->string('refrigerant_type')->nullable()->after('exhaust');
            }
            if (!Schema::hasColumn('unit', 'pdp')) {
                $table->string('pdp')->nullable()->after('refrigerant_type');
            }
        });
    }

    public function down()
    {
        Schema::table('unit', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model', 'type_unit', 'cooling', 'exhaust', 'refrigerant_type', 'pdp']);
        });
    }
};
