-- Jalankan di phpMyAdmin server
ALTER TABLE `unit_pm_template_items`
    MODIFY COLUMN `type` ENUM('part','custom','header') NOT NULL DEFAULT 'part';
