<?php

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

Route::get('/', function () {
    return view('welcome');
});

// Route::resource('/', 'UserController');
Route::get('/leads', [LeadsController::class, 'index']);
Route::get('/leads/detail', function(){
    return view('pages.sales.leads.detail');
});
Route::get('/quotation', [QuotationController::class, 'index']);
