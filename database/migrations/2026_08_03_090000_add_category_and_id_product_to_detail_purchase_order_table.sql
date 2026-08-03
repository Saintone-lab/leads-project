-- Jalankan di phpMyAdmin server
ALTER TABLE `detail_purchase_order`
    ADD COLUMN `category` VARCHAR(255) NOT NULL DEFAULT 'Sparepart' AFTER `id_unit`,
    ADD COLUMN `id_product` BIGINT UNSIGNED NULL AFTER `category`,
    ADD CONSTRAINT `detail_purchase_order_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `product` (`id`);
