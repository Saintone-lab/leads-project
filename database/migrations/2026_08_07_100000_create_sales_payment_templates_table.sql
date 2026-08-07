-- Jalankan di phpMyAdmin server
CREATE TABLE `sales_payment_templates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_sales` BIGINT UNSIGNED NOT NULL,
    `id_client` BIGINT UNSIGNED NULL,
    `name` VARCHAR(255) NOT NULL,
    `payment_term` TEXT NOT NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `sales_payment_templates_id_sales_foreign` FOREIGN KEY (`id_sales`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `sales_payment_templates_id_client_foreign` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
