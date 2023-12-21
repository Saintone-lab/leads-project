<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\QuotationController;

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
Route::get('/', [DashboardController::class,'index']);

// Route Reports
Route::get('/reports', function () {
    return view('pages.sales.report.index');
});

// Route For Leads
Route::resource('/leads', LeadsController::class);
Route::get('/leads/detail/{id}', [LeadsController::class, 'show'])->name('detail.leads');
Route::post('/leads/action/{id}', [LeadsController::class,'storeActionWithLeads'])->name('action.leads');

// Route untuk Quotation
Route::resource('/quotation', QuotationController::class);
Route::get('/quotation/leads/create', [QuotationController::class, 'create'])->name('create.quotation');
Route::get('/quotation/{id}/change_status', [QuotationController::class, 'change_status'])->name('status.change.quotation');
Route::get('/quotation/revision/{id}', [QuotationController::class, 'edit_revisi'])->name('revisi.quotation');
Route::get('/quotation/print/{id}', [QuotationController::class, 'print_quote'])->name('print.quotation');
Route::get('/quotation/pdf/{id}', [QuotationController::class, 'pdf_quote'])->name('pdf.quotation');

// Route untuk Visit
Route::get('/visits/leads', function(){
    return view('pages.sales.visits.index');
});

// Route untuk User
Route::get('/register', function(){
    return view('components.modal.register');
});
Route::get('/login', function(){
    return view('components.modal.login');
});

// Route untuk existing
Route::get('/existing', function(){
    return view('pages.sales.existing.index');
})->name('existing.sales');


// Route untuk PO
Route::get('/pending-po', function(){
    return view('pages.sales.po.pending.index');
});

// Route untuk Admin
Route::get('/dashboard/admin', function(){
    return view('pages.admin.dashboard');
})->name('dashboard.admin');

Route::get('/reports/admin', function(){
    return view('pages.admin.report');
})->name('reports.admin');

