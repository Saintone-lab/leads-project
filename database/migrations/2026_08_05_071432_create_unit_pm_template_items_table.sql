-- Jalankan di phpMyAdmin server
CREATE TABLE `unit_pm_template_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `id_unit` BIGINT UNSIGNED NOT NULL,
    `level` ENUM('PM1','PM2','PM3','PM4') NOT NULL,
    `type` ENUM('part','custom') NOT NULL DEFAULT 'part',
    `id_equivalent` BIGINT UNSIGNED NULL,
    `label` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `qty` DECIMAL(15,2) NOT NULL DEFAULT 1,
    `info_qty` VARCHAR(255) NOT NULL DEFAULT 'Pcs',
    `price` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `sort_order` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `unit_pm_template_items_id_unit_level_index` (`id_unit`, `level`),
    CONSTRAINT `unit_pm_template_items_id_unit_foreign` FOREIGN KEY (`id_unit`) REFERENCES `unit` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
