-- Jalankan di phpMyAdmin server
-- PENTING: harus dijalankan SETELAH 2026_07_21_100000 (butuh kolom doc_charged & shipping_charged).
ALTER TABLE `pending_po`
    ADD COLUMN `doc_recipient_id` BIGINT UNSIGNED NULL AFTER `doc_charged`,
    ADD COLUMN `shipping_recipient_id` BIGINT UNSIGNED NULL AFTER `shipping_charged`;
