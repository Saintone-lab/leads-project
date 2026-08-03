-- Jalankan di phpMyAdmin server
-- PERHATIAN: ini mengubah tipe kolom `type` dari ENUM('CT','DP','BP') menjadi VARCHAR(50).
-- Data existing yang bernilai 'CT'/'DP'/'BP' tetap aman (string sama), tapi constraint ENUM
-- akan hilang setelah ini dijalankan (kolom bisa diisi value bebas apapun ke depannya).
-- Migration asli menggunakan raw SQL langsung (bukan Blueprint), disalin apa adanya di bawah ini.
ALTER TABLE `invoice` MODIFY COLUMN `type` VARCHAR(50) NULL DEFAULT 'CT';
