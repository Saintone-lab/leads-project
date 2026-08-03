-- Jalankan di phpMyAdmin server
ALTER TABLE `invoice`
    ADD COLUMN `rejected_at` TIMESTAMP NULL AFTER `no_invoice`,
    ADD COLUMN `rejected_reason` TEXT NULL AFTER `rejected_at`,
    ADD COLUMN `rejected_by` BIGINT UNSIGNED NULL AFTER `rejected_reason`;
