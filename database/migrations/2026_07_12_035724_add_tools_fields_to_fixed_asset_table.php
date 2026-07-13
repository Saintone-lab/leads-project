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
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tools_master')->nullable()->after('atas_nama');
            $table->unsignedBigInteger('id_pic')->nullable()->after('id_tools_master');
            $table->string('foto_awal')->nullable()->after('id_pic');
            $table->date('tanggal_serah_terima')->nullable()->after('foto_awal');
            $table->enum('status_tools', ['Aktif', 'Retired'])->default('Aktif')->after('tanggal_serah_terima');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fixed_asset', function (Blueprint $table) {
            $table->dropColumn(['id_tools_master', 'id_pic', 'foto_awal', 'tanggal_serah_terima', 'status_tools']);
        });
    }
};
