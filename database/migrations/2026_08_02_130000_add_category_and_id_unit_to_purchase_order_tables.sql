-- Jalankan di phpMyAdmin server
ALTER TABLE `purchase_order`
    ADD COLUMN `category` VARCHAR(255) NOT NULL DEFAULT 'Sparepart' AFTER `no_po`,
    ADD COLUMN `receipt_status` VARCHAR(255) NOT NULL DEFAULT 'Pending' AFTER `category`;

ALTER TABLE `detail_purchase_order`
    ADD COLUMN `id_unit` BIGINT UNSIGNED NULL AFTER `product`,
    ADD CONSTRAINT `detail_purchase_order_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `unit` (`id`);
