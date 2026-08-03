-- Jalankan di phpMyAdmin server
-- Ada 2 FOREIGN KEY: ke `unit_quotation` dan ke `users`. Pastikan kedua tabel itu sudah ada di server
-- sebelum menjalankan ini.
CREATE TABLE `unit_quotation_comments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_unit_quotation` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `comment` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `unit_quotation_comments_id_unit_quotation_foreign` FOREIGN KEY (`id_unit_quotation`) REFERENCES `unit_quotation` (`id`) ON DELETE CASCADE,
    CONSTRAINT `unit_quotation_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
