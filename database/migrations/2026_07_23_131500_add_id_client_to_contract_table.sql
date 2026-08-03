-- Jalankan di phpMyAdmin server
-- Butuh kolom `id_unit_quotation` sudah ada di tabel `contract` (dipakai sebagai AFTER),
-- dan tabel `client` harus sudah ada karena ada FOREIGN KEY ke situ.
ALTER TABLE `contract`
    ADD COLUMN `id_client` BIGINT UNSIGNED NULL AFTER `id_unit_quotation`;

ALTER TABLE `contract`
    ADD CONSTRAINT `contract_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`) ON DELETE SET NULL;
