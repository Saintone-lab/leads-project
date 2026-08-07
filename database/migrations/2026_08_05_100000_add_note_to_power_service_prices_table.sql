-- Jalankan di phpMyAdmin server
ALTER TABLE `power_service_prices`
    ADD COLUMN `note_pm1` TEXT NULL,
    ADD COLUMN `note_pm2` TEXT NULL,
    ADD COLUMN `note_pm3` TEXT NULL,
    ADD COLUMN `note_pm4` TEXT NULL;
