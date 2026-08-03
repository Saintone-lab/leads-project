-- Jalankan di phpMyAdmin server
-- Migration asli tidak menentukan posisi kolom (tanpa ->after()), jadi kolom ditambahkan di akhir tabel.
ALTER TABLE `sparepart`
    ADD COLUMN `pm_level` ENUM('PM1','PM2','PM3','PM4') NOT NULL DEFAULT 'PM1';
