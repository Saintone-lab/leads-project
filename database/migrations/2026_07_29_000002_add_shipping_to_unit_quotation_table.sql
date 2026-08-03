-- Jalankan di phpMyAdmin server
-- Butuh kolom `tax_amount` sudah ada di tabel `unit_quotation`.
ALTER TABLE `unit_quotation`
    ADD COLUMN `shipping` DECIMAL(15,2) NOT NULL DEFAULT 0 AFTER `tax_amount`;
