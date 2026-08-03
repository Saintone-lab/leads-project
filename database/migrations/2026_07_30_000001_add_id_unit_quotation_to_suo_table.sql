-- Jalankan di phpMyAdmin server
-- Migration asli bersifat defensif (ada pengecekan Schema::hasColumn('suo', 'id_unit_quotation')
-- sebelum ALTER) — artinya kolom ini KEMUNGKINAN sudah ada di server. Cek dulu manual
-- (DESCRIBE `suo`) sebelum menjalankan, skip kalau kolom sudah ada.
-- Butuh kolom `id_quotation` sudah ada di tabel `suo`, dan tabel `unit_quotation` harus sudah ada
-- karena ada FOREIGN KEY ke situ.
ALTER TABLE `suo`
    ADD COLUMN `id_unit_quotation` BIGINT UNSIGNED NULL AFTER `id_quotation`;

ALTER TABLE `suo`
    ADD CONSTRAINT `suo_id_unit_quotation_foreign` FOREIGN KEY (`id_unit_quotation`) REFERENCES `unit_quotation` (`id`) ON DELETE SET NULL;
