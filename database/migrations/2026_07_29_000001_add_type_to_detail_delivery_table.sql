-- Jalankan di phpMyAdmin server
ALTER TABLE `detail_delivery`
    ADD COLUMN `type` VARCHAR(255) NOT NULL DEFAULT 'item' AFTER `id_delivery`;
