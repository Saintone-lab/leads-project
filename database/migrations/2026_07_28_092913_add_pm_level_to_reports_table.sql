-- Jalankan di phpMyAdmin server
ALTER TABLE `reports`
    ADD COLUMN `pm_level` VARCHAR(255) NULL AFTER `type`;
