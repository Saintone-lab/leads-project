-- Jalankan di phpMyAdmin server
-- PENTING: harus dijalankan SETELAH 2026_07_22_090000 (tabel helpdesk_tickets harus sudah ada).
ALTER TABLE `helpdesk_tickets`
    ADD COLUMN `resolution_note` TEXT NULL AFTER `status`;
