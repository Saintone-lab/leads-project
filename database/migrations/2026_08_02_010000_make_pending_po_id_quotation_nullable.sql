-- Jalankan di phpMyAdmin server
-- Migration asli bersifat defensif (cek Schema::hasTable & Schema::hasColumn sebelum jalan).
-- Pastikan tabel `pending_po` dan kolom `id_quotation` sudah ada di server sebelum menjalankan ini.
-- PERHATIAN: mengubah id_quotation dari NOT NULL menjadi NULLABLE — aman untuk data existing
-- (tidak mengubah value), hanya melonggarkan constraint.
ALTER TABLE `pending_po` MODIFY `id_quotation` BIGINT UNSIGNED NULL;
