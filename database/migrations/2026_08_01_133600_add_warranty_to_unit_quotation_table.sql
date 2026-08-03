-- Jalankan di phpMyAdmin server
-- Butuh kolom `pricing` sudah ada di tabel `unit_quotation`.
ALTER TABLE `unit_quotation`
    ADD COLUMN `warranty` VARCHAR(255) NULL AFTER `pricing`;
