-- Jalankan di phpMyAdmin server
ALTER TABLE `sales_payment_templates`
    ADD COLUMN `client_ids` TEXT NULL AFTER `id_client`;
