-- Jalankan di phpMyAdmin server
-- Butuh kolom `date` sudah ada di tabel `unit_quotation`.
ALTER TABLE `unit_quotation`
    ADD COLUMN `expired_date` DATE NULL AFTER `date`;
