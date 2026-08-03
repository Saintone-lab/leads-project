-- Jalankan di phpMyAdmin server
-- Catatan: kolom ini juga di-posisikan AFTER `id_delivery`, sama seperti migration
-- 2026_07_29_000001_add_type_to_detail_delivery_table. Kalau file itu dijalankan lebih dulu,
-- kolom `id_unit_quotation_detail` akan disisipkan DI ANTARA `id_delivery` dan `type`
-- (bukan setelah `type`) — ini sesuai urutan asli Laravel migration, tidak berbahaya, hanya
-- perlu diperhatikan urutan kolom akhir di tabel tidak akan 100% sama dengan urutan file migration.
ALTER TABLE `detail_delivery`
    ADD COLUMN `id_unit_quotation_detail` BIGINT UNSIGNED NULL AFTER `id_delivery`;
