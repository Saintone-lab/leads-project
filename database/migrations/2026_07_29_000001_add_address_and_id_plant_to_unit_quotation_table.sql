-- Jalankan di phpMyAdmin server
-- Butuh kolom `id_pic` dan `attn` sudah ada di tabel `unit_quotation`.
-- Ada FOREIGN KEY ke `client_plants`, pastikan tabel itu sudah ada di server.
ALTER TABLE `unit_quotation`
    ADD COLUMN `id_plant` BIGINT UNSIGNED NULL AFTER `id_pic`,
    ADD COLUMN `address` TEXT NULL AFTER `attn`;

ALTER TABLE `unit_quotation`
    ADD CONSTRAINT `unit_quotation_id_plant_foreign` FOREIGN KEY (`id_plant`) REFERENCES `client_plants` (`id`) ON DELETE SET NULL;
