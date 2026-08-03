-- Jalankan di phpMyAdmin server
CREATE TABLE `detail_unit_product_out` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_unit_product_out` BIGINT UNSIGNED NOT NULL,
    `source_type` ENUM('unit_inventory','fixed_asset') NOT NULL,
    `id_unit_inventory` BIGINT UNSIGNED NULL,
    `id_fixed_asset` BIGINT UNSIGNED NULL,
    `harga_jual` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `nilai_pokok` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `selisih` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `detail_unit_product_out_id_unit_product_out_foreign` FOREIGN KEY (`id_unit_product_out`) REFERENCES `unit_product_out` (`id`) ON DELETE CASCADE,
    CONSTRAINT `detail_unit_product_out_id_unit_inventory_foreign` FOREIGN KEY (`id_unit_inventory`) REFERENCES `unit_inventory` (`id`),
    CONSTRAINT `detail_unit_product_out_id_fixed_asset_foreign` FOREIGN KEY (`id_fixed_asset`) REFERENCES `fixed_asset` (`id`)
);
