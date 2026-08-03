-- Jalankan di phpMyAdmin server
-- Catatan: nama file migration "contract_visit_schedules" (jamak) tapi nama tabel yang benar-benar
-- dibuat di Schema::create() adalah `contract_visit_schedule` (tunggal). Ada FOREIGN KEY ke `contract`,
-- pastikan tabel `contract` sudah ada di server sebelum menjalankan ini.
CREATE TABLE `contract_visit_schedule` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_contract` BIGINT UNSIGNED NOT NULL,
    `visit_number` INT NOT NULL,
    `planned_date` DATE NOT NULL,
    `estimated_revenue` BIGINT NOT NULL,
    `status` ENUM('Pending','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `contract_visit_schedule_id_contract_foreign` FOREIGN KEY (`id_contract`) REFERENCES `contract` (`id`) ON DELETE CASCADE
);
