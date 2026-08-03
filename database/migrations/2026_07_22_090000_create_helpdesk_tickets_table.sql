-- Jalankan di phpMyAdmin server
-- Catatan: id_user dibuat via $table->foreignId('id_user') TANPA ->constrained(), jadi TIDAK ada
-- foreign key constraint di migration asli (konsisten dengan pola project: foreignId sering tanpa FK/index).
CREATE TABLE `helpdesk_tickets` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `no_ticket` VARCHAR(255) NOT NULL UNIQUE,
    `id_user` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` LONGTEXT NOT NULL,
    `status` ENUM('Open','In Progress','Resolved') NOT NULL DEFAULT 'Open',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
