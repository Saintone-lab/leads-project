-- Jalankan di phpMyAdmin server, urutan sesuai baris di bawah.
-- Setara dengan migration: 2026_07_23_000000_add_diskon_type_to_unit_quotation_table.php

-- 1. Lebarkan kolom diskon: decimal(5,2) (maks 999.99) -> decimal(15,2)
--    supaya bisa menyimpan nominal Rupiah, bukan cuma persen.
ALTER TABLE unit_quotation MODIFY diskon DECIMAL(15,2) NOT NULL DEFAULT 0.00;

-- 2. Tambah kolom diskon_type buat nandain tipe diskon per baris.
ALTER TABLE unit_quotation ADD COLUMN diskon_type ENUM('percent', 'amount') NOT NULL DEFAULT 'percent' AFTER diskon;
