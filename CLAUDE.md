# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Frontend development
npm run dev        # Start Vite dev server with HMR
npm run build      # Build frontend assets for production

# Backend development
php artisan serve              # Start Laravel dev server
php artisan migrate            # Run database migrations
php artisan migrate:rollback   # Rollback last migration batch
php artisan db:seed            # Run database seeders
php artisan cache:clear        # Clear application cache
php artisan route:list         # List all registered routes

# Testing
php artisan test               # Run PHPUnit test suite
php artisan test --filter=TestName   # Run a single test
```

## Architecture

This is a Laravel 9 + Vite monolith — a CRM/ERP system managing sales pipelines, accounting, warehouse, and service monitoring with role-based access control.

**Stack:** Laravel 9 (PHP 8.0+), Blade templates, Bootstrap 5, Axios, MySQL (Eloquent ORM), Laravel Sanctum, DomPDF, Intervention/Image.

### Business Domain Flow

The core business pipeline: **Leads → Prospects → Quotations → Purchase Orders → Invoices → Payments**

Modules and their controllers:
- **Sales/CRM**: `LeadsController`, `ProspectController`, `QuotationController`, `CustomersController`
- **Accounting**: `InvoiceController`, `PaymentController`, `PayableController`, `ExpenseController`
- **Warehouse**: `ProductController`, `ProductInController`, `ProductOutController`, `StockController`, `WarehouseController`
- **Logistics**: `DeliveryController`, `PendingController`
- **Operations**: `MonitoringController`, `MachineController`, `ContractController`, `AuditController`
- **Admin/HR**: `DashboardController`, `UserController`, `EmployeeController`
- **Reporting**: `ReportsController`, `SalesReportController`, `NotulenController`

### Role-Based Access

Roles: `Sales`, `Support`, `Admin`, `Accounting`, `Coordinator`, `Technician`, `Warehouse`. Access control is enforced in controllers and Blade templates via `Auth::user()->role`. The `DashboardController` is the central router — it conditionally renders different views per role.

### Frontend Pattern

No SPA framework. All rendering is server-side Blade. Client-side interactivity uses:
- Bootstrap 5 modals and components
- Axios (configured in `resources/js/bootstrap.js`) for AJAX calls
- CSRF token is injected globally via the meta tag in the base layout

Blade structure: `resources/views/layouts/app.blade.php` is the base layout. Module views live under `resources/views/pages/{module}/`.

### Key Conventions

- **Routes**: `routes/web.php` is the single large routing file (5000+ lines). Routes are grouped by module/role.
- **Models**: 88+ Eloquent models in `app/Models/`. Relationships follow standard Eloquent conventions.
- **Form validation**: Uses Laravel Form Request classes in `app/Http/Requests/`.
- **PDF generation**: Uses `barryvdh/laravel-dompdf` — controllers return `PDF::loadView(...)`.
- **File uploads**: Uses Intervention/Image for processing; files stored via Laravel's `Storage` facade.
- **Payment types**: DP (down payment) and Tempo are the two core payment structures used throughout accounting logic.

### Legacy Code

`app/api/leads/` and `resources/api/` contain legacy PHP files with direct PDO database connections (hardcoded to `db_leads_v1`). These predate the Laravel structure — avoid extending them; migrate logic to controllers/models when touching those areas.
