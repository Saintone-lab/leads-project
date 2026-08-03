-- Jalankan di phpMyAdmin server
CREATE TABLE `unit_product_in` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `no_transaksi` VARCHAR(255) NOT NULL UNIQUE,
    `transaction_type` ENUM('purchase_new','purchase_used','trade_in') NOT NULL,
    `id_po` BIGINT UNSIGNED NULL,
    `id_supplier` BIGINT UNSIGNED NULL,
    `id_customer` VARCHAR(255) NULL,
    `date` DATE NOT NULL,
    `note` TEXT NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `unit_product_in_id_po_foreign` FOREIGN KEY (`id_po`) REFERENCES `purchase_order` (`id`),
    CONSTRAINT `unit_product_in_id_supplier_foreign` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`),
    CONSTRAINT `unit_product_in_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
);
