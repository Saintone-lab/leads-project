-- Jalankan di phpMyAdmin server
ALTER TABLE `power_service_prices`
    ADD COLUMN `desc_pm1` TEXT NULL,
    ADD COLUMN `desc_pm2` TEXT NULL,
    ADD COLUMN `desc_pm3` TEXT NULL,
    ADD COLUMN `desc_pm4` TEXT NULL;
