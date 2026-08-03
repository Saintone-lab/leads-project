-- Jalankan di phpMyAdmin server
-- Migration asli bersifat defensif (ada pengecekan Schema::hasColumn('pending_po', 'id_unit_quotation')
-- sebelum ALTER) — artinya kolom ini KEMUNGKINAN sudah ada di server. Cek dulu manual
-- (DESCRIBE `pending_po`) sebelum menjalankan, skip kalau kolom sudah ada.
-- Butuh kolom `id_quotation` sudah ada di tabel `pending_po`. Tidak ada FOREIGN KEY (sesuai kode asli,
-- hanya unsignedBigInteger biasa tanpa ->constrained()).
ALTER TABLE `pending_po`
    ADD COLUMN `id_unit_quotation` BIGINT UNSIGNED NULL AFTER `id_quotation`;
