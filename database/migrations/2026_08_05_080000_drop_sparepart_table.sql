-- Jalankan di phpMyAdmin server
-- Consumable/Non-Consumable Part per unit (tabel sparepart) diganti model baru:
-- template PM disusun manual per unit+level di tabel unit_pm_template_items,
-- dan "Tambah Item" sekarang search langsung ke master product/serial_product (Product Equivalent).
DROP TABLE IF EXISTS `sparepart`;
