-- Jalankan di phpMyAdmin server
-- PENTING: harus dijalankan SETELAH 2026_07_21_091457 (butuh kolom doc_address_manual & shipping_address_manual)
-- dan SEBELUM 2026_07_22_033619 (kolom doc_charged/shipping_charged dipakai sebagai AFTER di migration itu).
ALTER TABLE `pending_po`
    ADD COLUMN `doc_charged` INT NULL AFTER `doc_address_manual`,
    ADD COLUMN `shipping_charged` INT NULL AFTER `shipping_address_manual`;
