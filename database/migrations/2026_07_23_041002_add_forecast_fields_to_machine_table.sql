-- Jalankan di phpMyAdmin server
-- Migration asli tidak menentukan posisi kolom (tanpa ->after()), jadi kolom ditambahkan di akhir tabel.
ALTER TABLE `machine`
    ADD COLUMN `is_forecasted` TINYINT(1) NOT NULL DEFAULT 1,
    ADD COLUMN `forecast_type` ENUM('parts','regular_service','contract') NOT NULL DEFAULT 'regular_service',
    ADD COLUMN `last_service_date` DATE NULL;
