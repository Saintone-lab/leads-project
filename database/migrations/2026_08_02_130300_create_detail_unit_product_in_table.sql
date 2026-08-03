-- Jalankan di phpMyAdmin server
CREATE TABLE `detail_unit_product_in` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_unit_product_in` BIGINT UNSIGNED NOT NULL,
    `id_unit` BIGINT UNSIGNED NOT NULL,
    `serial_number` VARCHAR(255) NULL,
    `harga` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `biaya_tambahan` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `kondisi` VARCHAR(255) NULL,
    `id_unit_inventory` BIGINT UNSIGNED NULL,
    `id_fixed_asset` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `detail_unit_product_in_id_unit_product_in_foreign` FOREIGN KEY (`id_unit_product_in`) REFERENCES `unit_product_in` (`id`) ON DELETE CASCADE,
    CONSTRAINT `detail_unit_product_in_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `unit` (`id`),
    CONSTRAINT `detail_unit_product_in_id_unit_inventory_foreign` FOREIGN KEY (`id_unit_inventory`) REFERENCES `unit_inventory` (`id`),
    CONSTRAINT `detail_unit_product_in_id_fixed_asset_foreign` FOREIGN KEY (`id_fixed_asset`) REFERENCES `fixed_asset` (`id`)
);
