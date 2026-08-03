-- Jalankan di phpMyAdmin server
-- Ada FOREIGN KEY ke `machine`, pastikan tabel `machine` sudah ada di server sebelum menjalankan ini.
CREATE TABLE `forecast_history` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_machine` BIGINT UNSIGNED NOT NULL,
    `year` INT NOT NULL,
    `forecast_type` VARCHAR(255) NOT NULL DEFAULT 'parts',
    `is_forecasted` TINYINT(1) NOT NULL DEFAULT 1,
    `visit_1_type` VARCHAR(255) NULL,
    `visit_1_date` DATE NULL,
    `visit_2_type` VARCHAR(255) NULL,
    `visit_2_date` DATE NULL,
    `visit_3_type` VARCHAR(255) NULL,
    `visit_3_date` DATE NULL,
    `visit_4_type` VARCHAR(255) NULL,
    `visit_4_date` DATE NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    UNIQUE KEY `forecast_history_id_machine_year_unique` (`id_machine`, `year`),
    CONSTRAINT `forecast_history_id_machine_foreign` FOREIGN KEY (`id_machine`) REFERENCES `machine` (`id`) ON DELETE CASCADE
);
