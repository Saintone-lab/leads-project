-- Jalankan di phpMyAdmin server
CREATE TABLE `power_service_prices` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `power` VARCHAR(255) NOT NULL UNIQUE,
    `price_pm1` BIGINT NOT NULL DEFAULT 0,
    `price_pm2` BIGINT NOT NULL DEFAULT 0,
    `price_pm3` BIGINT NOT NULL DEFAULT 0,
    `price_pm4` BIGINT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
