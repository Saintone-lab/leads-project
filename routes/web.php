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
Route::get('/quotation/leads', [QuotationController::class, 'index']);
Route::get('/quotation/leads/create', [QuotationController::class, 'create']);

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

// Route untuk PO
Route::get('/pending-po', function(){
    return view('pages.sales.po.pending.index');
});


