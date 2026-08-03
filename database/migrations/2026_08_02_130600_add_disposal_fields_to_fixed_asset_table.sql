-- Jalankan di phpMyAdmin server
ALTER TABLE `fixed_asset`
    ADD COLUMN `is_disposed` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status_unit`,
    ADD COLUMN `tanggal_disposal` DATE NULL AFTER `is_disposed`,
    ADD COLUMN `nilai_buku_disposal` DECIMAL(15,2) NULL AFTER `tanggal_disposal`,
    ADD COLUMN `harga_jual_final` DECIMAL(15,2) NULL AFTER `nilai_buku_disposal`;
