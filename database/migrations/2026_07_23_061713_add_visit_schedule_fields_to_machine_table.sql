-- Jalankan di phpMyAdmin server
-- Migration asli tidak menentukan posisi kolom (tanpa ->after()), jadi kolom ditambahkan di akhir tabel.
ALTER TABLE `machine`
    ADD COLUMN `visit_1_type` ENUM('PM1','PM2') NULL,
    ADD COLUMN `visit_1_date` DATE NULL,
    ADD COLUMN `visit_2_type` ENUM('PM1','PM2') NULL,
    ADD COLUMN `visit_2_date` DATE NULL,
    ADD COLUMN `visit_3_type` ENUM('PM1','PM2') NULL,
    ADD COLUMN `visit_3_date` DATE NULL,
    ADD COLUMN `visit_4_type` ENUM('PM1','PM2') NULL,
    ADD COLUMN `visit_4_date` DATE NULL;
