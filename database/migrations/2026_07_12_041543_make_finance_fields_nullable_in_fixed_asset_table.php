<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * fixed_asset awalnya dirancang khusus untuk aset yang selalu
     * dicatat lewat form akuntansi penuh (Kendaraan, Mesin), jadi
     * kolom-kolom akun/invoice/pembayaran NOT NULL tanpa default.
     * Kategori "Tools" milik teknisi tidak punya pencatatan akun
     * per-unit seperti itu, jadi kolom ini perlu boleh kosong.
     * Pakai raw SQL karena doctrine/dbal belum terpasang di project ini.
     */
    public function up()
    {
        DB::statement("ALTER TABLE fixed_asset
            MODIFY id_aktiva BIGINT UNSIGNED NULL,
            MODIFY id_penyusutan BIGINT UNSIGNED NULL,
            MODIFY id_beban BIGINT UNSIGNED NULL,
            MODIFY id_supplier BIGINT UNSIGNED NULL,
            MODIFY id_pengeluaran BIGINT UNSIGNED NULL,
            MODIFY no_invoice VARCHAR(255) NULL,
            MODIFY beli DATE NULL,
            MODIFY pakai DATE NULL,
            MODIFY bayar DATE NULL,
            MODIFY metode VARCHAR(255) NULL,
            MODIFY code VARCHAR(255) NULL,
            MODIFY `desc` VARCHAR(255) NULL,
            MODIFY umur INT NULL,
            MODIFY total INT NULL
        ");
    }

    public function down()
    {
        // Sengaja tidak dikembalikan ke NOT NULL — akan gagal kalau sudah
        // ada baris Tools dengan kolom-kolom ini kosong.
    }
};
