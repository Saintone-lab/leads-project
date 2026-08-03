-- Jalankan di phpMyAdmin server
-- Migration asli tidak menentukan posisi kolom (tanpa ->after()), jadi kolom ditambahkan di akhir tabel.
ALTER TABLE `purchase_request`
    ADD COLUMN `qty_received` INT NULL,
    ADD COLUMN `gr_status` VARCHAR(255) NULL,
    ADD COLUMN `gr_note` TEXT NULL,
    ADD COLUMN `no_do` VARCHAR(255) NULL,
    ADD COLUMN `gr_date` DATE NULL;
