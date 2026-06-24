<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Pastikan tidak ada NULL sebelum ubah ke BIGINT NOT NULL
        DB::statement('UPDATE quotation SET tax = 0 WHERE tax IS NULL');
        DB::statement('UPDATE quotation SET shipping = 0 WHERE shipping IS NULL');
        DB::statement('UPDATE quotation SET diskon = 0 WHERE diskon IS NULL');
        DB::statement('UPDATE quotation SET fee = 0 WHERE fee IS NULL');
        DB::statement('UPDATE quotation SET nett = 0 WHERE nett IS NULL');
        DB::statement('UPDATE quotation SET subtotal = 0 WHERE subtotal IS NULL');
        DB::statement('UPDATE quotation SET total_no_tax = 0 WHERE total_no_tax IS NULL');
        DB::statement('UPDATE quotation SET harga_total = 0 WHERE harga_total IS NULL');

        DB::statement('UPDATE detail_quotation SET price = 0 WHERE price IS NULL');
        DB::statement('UPDATE detail_quotation SET amount = 0 WHERE amount IS NULL');
        DB::statement('UPDATE detail_quotation SET fee = 0 WHERE fee IS NULL');
        DB::statement('UPDATE detail_quotation SET pph = 0 WHERE pph IS NULL');

        DB::statement('ALTER TABLE quotation
            MODIFY COLUMN tax BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN shipping BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN diskon BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN fee BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN nett BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN subtotal BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN total_no_tax BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN harga_total BIGINT NOT NULL DEFAULT 0
        ');

        DB::statement('ALTER TABLE detail_quotation
            MODIFY COLUMN price BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN amount BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN fee BIGINT NOT NULL DEFAULT 0,
            MODIFY COLUMN pph BIGINT NOT NULL DEFAULT 0
        ');
    }

    public function down()
    {
        DB::statement('ALTER TABLE quotation
            MODIFY COLUMN tax INT NOT NULL,
            MODIFY COLUMN shipping INT NOT NULL,
            MODIFY COLUMN diskon INT NOT NULL,
            MODIFY COLUMN fee INT NOT NULL,
            MODIFY COLUMN nett INT NOT NULL,
            MODIFY COLUMN subtotal INT NOT NULL,
            MODIFY COLUMN total_no_tax INT NOT NULL,
            MODIFY COLUMN harga_total INT NOT NULL
        ');

        DB::statement('ALTER TABLE detail_quotation
            MODIFY COLUMN price INT NOT NULL,
            MODIFY COLUMN amount INT NOT NULL,
            MODIFY COLUMN fee INT NOT NULL,
            MODIFY COLUMN pph INT NOT NULL
        ');
    }
};
