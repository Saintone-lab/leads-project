-- Jalankan di phpMyAdmin server
-- PERHATIAN: mengubah id_detail_service dari NOT NULL menjadi NULLABLE — aman untuk data existing
-- (tidak mengubah value), hanya melonggarkan constraint supaya alur Unit Quotation & Sparepart
-- Quotation (yang tidak mengisi field ini) tidak error SQLSTATE[HY000]: 1364.
ALTER TABLE `detail_pending_po` MODIFY `id_detail_service` BIGINT UNSIGNED NULL DEFAULT NULL;
