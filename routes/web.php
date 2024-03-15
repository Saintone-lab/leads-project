<?php

use App\Http\Controllers\ApiTableController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExistingController;
use App\Http\Controllers\PicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ServiceReportsController;
use App\Models\ProductIn;
use App\Models\SerialProduct;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route Dashboard
// Route::get('/', function () {
//     return view('pages.sales.dashboard');
// });
Route::group(["middleware" => "auth"], function () {
    Route::get('/', [DashboardController::class, 'index']);

    // Route User
    Route::resource('/profile', UserController::class);

    // Route Reports
    Route::get('/reports', [ReportsController::class, 'index']);

    // Route Overview
    Route::get('/overview', [DashboardController::class, 'overviewIndex']);

    // Route For Customers
    Route::resource('/customers', CustomersController::class);
    Route::get('/customers/detail/{id}', [CustomersController::class, 'show'])->name('detail.customers');

    // Route For Leads
    Route::resource('/leads', LeadsController::class);
    Route::get('/leads/detail/{id}', [LeadsController::class, 'show'])->name('detail.leads');
    Route::post('/leads/action/{id}', [LeadsController::class, 'storeActionWithLeads'])->name('action.leads');

    // Route untuk Quotation
    Route::resource('/quotation', QuotationController::class);
    Route::get('/quotation/leads/create', [QuotationController::class, 'create'])->name('create.quotation');
    Route::get('/po', [QuotationController::class, 'po_quote'])->name('quotation.po');
    Route::get('/quotation/{id}/change_status', [QuotationController::class, 'change_status'])->name('status.change.quotation');
    Route::get('/quotation/revision/{id}', [QuotationController::class, 'edit_revisi'])->name('revisi.quotation');
    Route::get('/quotation/print/{id}', [QuotationController::class, 'print_quote'])->name('print.quotation');
    Route::get('/quotation/pdf/{id}', [QuotationController::class, 'pdf_quote'])->name('pdf.quotation');

    // Route untuk Visit
    Route::get('/visits/leads', function () {
        return view('pages.sales.visits.index');
    });

    // Route untuk existing
    Route::resource('/existing', CrmController::class);
    Route::post('/existing/action/{id}', [CrmController::class, 'storeActionWithCrm'])->name('action.crm');
    Route::post('/existing/update-status/{id}', [CrmController::class, 'updateStatusAtDropdown'])->name('update-status.crm');

    // Route untuk service Reports
    Route::resource('/service-reports', ServiceReportsController::class);
    Route::get('/service-reports/print/{id}', [ServiceReportsController::class, 'print_reports'])->name('service-reports.print');
    Route::post('/service-reports/sign/{id}', [ServiceReportsController::class, 'hand_sign'])->name('service-reports.sign');
    Route::delete('/service-reports/del-sign/{id}', [ServiceReportsController::class, 'delete_hand_sign'])->name('service-reports.del-sign');

    // Route untuk service Reports
    Route::resource('/audit-tools', AuditController::class);
    Route::get('/audit-tools/print/{id}', [AuditController::class, 'print_audit'])->name('audit-tools.print');

    // Route untuk PO
    Route::get('/pending-po', function () {
        return view('pages.sales.po.pending.index');
    });

    // Route untuk Pic
    Route::resource('/pic', PicController::class);
    Route::post('/pic/leads/{id}', [PicController::class, 'storeOnLeads'])->name('pic.leads.store');
    Route::post('/pic/customers/store/{id}', [PicController::class, 'storeOnCust'])->name('pic.cust.store');
    Route::post('/pic/customers/update/{id}', [PicController::class, 'updateOnCust'])->name('pic.cust.update');
    Route::post('/pic/customers/{id}', [PicController::class, 'destroyOnCust'])->name('pic.cust.destroy');
    Route::post('/pic/existing/store/{id}', [PicController::class, 'storeOnCrm'])->name('pic.crm.store');
    Route::patch('/pic/existing/update/{id}', [PicController::class, 'updateOnCrm'])->name('pic.crm.update');
    Route::post('/pic/existing/{id}', [PicController::class, 'destroyOnCrm'])->name('pic.crm.destroy');

    // Route untuk Product
    Route::resource('/product', ProductController::class);
    Route::post('/product/equivalent/store/{id}', [ProductController::class, 'storeEquivalent'])->name('product.equivalent');
    Route::post('/product/replacement/store/{id}', [ProductController::class, 'storeReplacement'])->name('product.replacement');
    Route::delete('/product/equivalent/{id}', [ProductController::class, 'destroyEquivalent'])->name('product.equivalent.destroy');
    Route::delete('/product/replacement/{id}', [ProductController::class, 'destroyReplacement'])->name('product.replacement.destroy');

    // Route untuk Product
    Route::resource('/product-in', ProductInController::class);

    // Route untuk API Tabel DataTable
    // Route::get('/fetch-data/leads', [ApiTableController::class, 'tableLeads']);
    Route::get('/db/leads', function () {
        require_once base_path('app/api/leads/connection.php');
    });
    Route::get('/db/leads/admin', function () {
        require_once base_path('app/api/leads/connectionAdmin.php');
    });
    Route::get('/db/customers', function () {
        require_once base_path('app/api/customers/connection.php');
    });
    Route::get('/db/crm', function () {
        require_once base_path('app/api/crm/connection.php');
    });
    Route::get('/db/quotation', function () {
        require_once base_path('app/api/quotation/connection.php');
    });
    Route::get('/db/po', function () {
        require_once base_path('app/api/po/connection.php');
    });
    Route::get('/db/prospect', function () {
        require_once base_path('app/api/prospect/connection.php');
    });
    Route::get('/db/prospect/sales', function () {
        require_once base_path('app/api/prospect/connectionSales.php');
    });
    Route::get('/db/loss', function () {
        require_once base_path('app/api/lossQ/connection.php');
    });
    Route::get('/db/reports', function () {
        require_once base_path('app/api/reports/connection.php');
    });
    Route::get('/db/reports/admin', function () {
        require_once base_path('app/api/reports/connectionAdmin.php');
    });
    Route::get('/db/audit', function () {
        require_once base_path('app/api/audit/connection.php');
    });
    Route::get('/db/product', function () {
        require_once base_path('app/api/product/connection.php');
    });
    Route::get('/db/product/{id}', function ($id) {
        // Menggunakan Eloquent untuk mengambil data serial_product berdasarkan id
        $serialProduct = SerialProduct::where('id_product', $id)->get();
        // Mengembalikan data dalam bentuk JSON
        return response()->json(['data' => $serialProduct]);
    });
    Route::get('/db/productIn', function () {
        require_once base_path('app/api/product/in/connection.php');
    });
    Route::get('/db/product/sales', function () {
        require_once base_path('app/api/product/connectionSales.php');
    });
});
Auth::routes();
