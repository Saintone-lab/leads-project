-- Jalankan di phpMyAdmin server.
-- Setara dengan migration: 2026_07_28_000001_add_id_equivalent_to_unit_quotation_detail_table.php

ALTER TABLE unit_quotation_detail ADD COLUMN id_equivalent BIGINT UNSIGNED NULL AFTER id_fixed_asset;
