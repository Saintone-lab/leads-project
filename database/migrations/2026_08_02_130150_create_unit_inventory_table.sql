-- Jalankan di phpMyAdmin server
CREATE TABLE `unit_inventory` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_unit` BIGINT UNSIGNED NOT NULL,
    `serial_number` VARCHAR(255) NULL,
    `harga_modal` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `biaya_rebranding` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `total_modal` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `status` ENUM('available','sold') NOT NULL DEFAULT 'available',
    `id_unit_product_in` BIGINT UNSIGNED NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `unit_inventory_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `unit` (`id`),
    CONSTRAINT `unit_inventory_id_unit_product_in_foreign` FOREIGN KEY (`id_unit_product_in`) REFERENCES `unit_product_in` (`id`),
    CONSTRAINT `unit_inventory_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
);
