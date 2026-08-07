-- Jalankan di phpMyAdmin server
-- pm_level per-item sparepart diganti model baru: tabel unit_pm_template_items
-- (template PM disusun manual per unit+level, lihat migration create_unit_pm_template_items_table)
ALTER TABLE `sparepart`
    DROP COLUMN `pm_level`;
