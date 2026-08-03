-- Jalankan di phpMyAdmin server
CREATE TABLE `unit_product_out` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `no_transaksi` VARCHAR(255) NOT NULL UNIQUE,
    `date` DATE NOT NULL,
    `customer` VARCHAR(255) NULL,
    `note` TEXT NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `unit_product_out_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
);
