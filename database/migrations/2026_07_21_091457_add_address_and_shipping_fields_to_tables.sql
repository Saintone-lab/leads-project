-- Jalankan di phpMyAdmin server
-- PENTING: jalankan file ini SEBELUM 2026_07_21_100000 dan 2026_07_22_033619,
-- karena kedua migration itu memakai AFTER yang merujuk kolom yang dibuat di sini.
ALTER TABLE `pending_po`
    ADD COLUMN `doc_address_type` VARCHAR(255) NOT NULL DEFAULT 'customer' AFTER `charged`,
    ADD COLUMN `doc_address_manual` TEXT NULL AFTER `doc_address_type`,
    ADD COLUMN `shipping_address_type` VARCHAR(255) NOT NULL DEFAULT 'customer' AFTER `doc_address_manual`,
    ADD COLUMN `shipping_address_manual` TEXT NULL AFTER `shipping_address_type`,
    ADD COLUMN `combine_shipping_and_parts` TINYINT(1) NOT NULL DEFAULT 1 AFTER `shipping_address_manual`;

ALTER TABLE `expanse`
    ADD COLUMN `description` VARCHAR(255) NULL AFTER `cost`;
