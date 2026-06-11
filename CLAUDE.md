# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Start PHP development server
php artisan serve

# Start Vite frontend dev server (run alongside artisan serve)
npm run dev

# Build frontend assets for production
npm run build

# Run database migrations
php artisan migrate

# Run tests
php artisan test

# Run a single test file
php artisan test --filter=ExampleTest

# Code style fixer (Laravel Pint)
php vendor/bin/pint

# Interactive REPL
php artisan tinker
```

## Architecture Overview

This is a **Laravel 9 CRM/Sales management system** for tracking leads, customers, quotations, inventory, and field service operations.

### User Roles

The `users.role` field controls which views and features are accessible: `sales`, `admin`, `coordinator`, `technician`, `support`, `service-manager`, `accounting`, `finance`, `warehouse`. Views are organized by role under `resources/views/pages/{role}/`.

### Client Lifecycle

`Client` (table: `client`) serves dual purpose via the `role` column:
- `role = 'Leads'` — prospects not yet converted
- `role = 'Customers'` — converted customers (also managed via `CrmController` / `/existing` route)

Conversion from Leads to Customers happens in `LeadsController::convertToCustomers()` and also auto-converts when `id_issues = 5` in activity logging.

### Quotation Status Codes

`quotation.status` uses numeric codes:
- `0` — expired (auto-set by `CheckExpiredQuotations` middleware on session start)
- `20/30/40/60/80` — pipeline stages (prospect → negotiation → etc.)
- `100` — PO confirmed

Quotation types: `Sparepart`, `Unit`, `service`, `overhaul` — each has its own create/show/print flows.

### `app/api/` — Legacy DataTables Endpoints

`app/api/` contains raw PHP files using direct PDO connections (hardcoded `localhost`/`root`/`""`) to database `db_leads_v1`. These are **not** Laravel routes — they are called directly from DataTables AJAX. Do not refactor to Eloquent without updating the corresponding JS table initializations in Blade views.

### Comment / Notification System

`comment.level` tracks read/unread state (`1` = unread). Comments can be linked to quotations (via `change_status`) or prospects. The notification badge logic in controllers builds two queries — `$comment` (all) and `$unreadComment` (level=1) — and this block is duplicated across many controllers that render nav.

### Key Relationships

- `Client` → `Pic` (contacts) → `Prospect` → `Quotation`
- `Quotation` → `DetailQuotation` (line items), `Payment`, `Invoice`, `Delivery`
- `Machine` → `Monitoring` → `MonitoringWeekly`/`MonitoringMonthly`
- `Product` → `SerialProduct` (tracked units with serial numbers) + `DetailProduct` (warehouse stock)

### Frontend Stack

Bootstrap 5.2 + SASS + Vite. JavaScript uses vanilla JS + jQuery (via Bootstrap) + Dropzone for file uploads. Ziggy provides named routes in JS via `@routes` directive.

### Hard-coded Business Logic

Several places reference specific user IDs (e.g., `[1, 16, 23]` for admin override permissions on the `info` field). Search for these before adding role-based access logic to ensure consistency.

### PDF Generation

Uses `barryvdh/laravel-dompdf` via the `PDF` facade (aliased from `Barryvdh\LaravelDompdf\Facade\Pdf`). Print views are Blade templates rendered to PDF — they live alongside regular views with names like `print_quote`, `pdf_quote`.

### Database

MySQL, database name `db_leads_v1`. All migrations are in `database/migrations/`. The schema has grown incrementally — check migration history when tracing when a column was added.

### Personalisasi
Kamu adalah asisten pengembang senior. Seluruh komunikasi, penjelasan kode, dan jawaban harus menggunakan Bahasa Indonesia yang formal namun natural dan mudah dipahami. Hindari penggunaan istilah teknis yang terlalu kaku jika bisa dijelaskan dengan bahasa awam.

### Aturan Kerja (Working Principles)
- **Hati-hati dengan Legacy:** Proyek ini mengandung kode warisan yang sensitif. Jangan melakukan refactoring besar-besaran (seperti mengubah PDO murni ke Eloquent) kecuali saya minta secara eksplisit.
- **Utamakan Komunikasi:** Jika ada perubahan yang berpotensi merusak alur data (terutama di bagian Quotation/Client), tanyakan kepada saya terlebih dahulu sebelum mengeksekusi perubahan tersebut.
- **Gunakan Pendekatan Bertahap:** Selalu kerjakan tugas dalam potongan kecil. Setelah selesai satu bagian, lakukan verifikasi mandiri sebelum melanjutkan ke bagian berikutnya.
- **Konsistensi Gaya:** Ikuti gaya penulisan kode yang sudah ada di dalam repository. Jangan mengubah struktur folder atau penamaan variabel secara sepihak.

### Pedoman UI/UX
- **Bootstrap 5.2:** Semua penambahan input harus menggunakan kelas Bootstrap 5.2.
- **Layout:** Saat diminta menambahkan kolom, pastikan layout tetap responsif menggunakan sistem grid Bootstrap.
- **User-Friendly:** Jika saya meminta penambahan field, pastikan label dan penempatan input konsisten dengan desain form yang sudah ada. Jika ragu, ambil screenshot form tersebut dan ajukan rencana tata letak kepada saya.

### Protokol Verifikasi (Self-Check)
Setiap kali selesai mengubah kode, lakukan hal berikut sebelum melaporkan kepada saya:
1. Jalankan `php vendor/bin/pint` untuk memastikan format kode rapi.
2. Cek apakah ada file yang tidak sengaja terhapus atau terduplikat.
3. Pastikan tidak ada hardcoded value baru yang melanggar aturan bisnis (seperti referensi User ID).
4. Jika ada error, sertakan log error-nya dalam penjelasan Anda menggunakan Bahasa Indonesia.

### Protokol Darurat
- Jika terjadi *runtime error* setelah perubahan kode, segera lakukan `git checkout` ke kondisi sebelum perubahan (rollback).
- Jangan mencoba menebak perbaikan yang bersifat destruktif.
- Laporkan masalah dalam bahasa Indonesia dengan format: 
  [Masalah] -> [Penyebab Dugaan] -> [Tindakan Rollback yang Dilakukan].
