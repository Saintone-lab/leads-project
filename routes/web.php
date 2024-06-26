<?php

use App\Http\Controllers\ApiTableController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExistingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\PicController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProductOutController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\ServiceReportsController;
use App\Http\Controllers\StockController;
use App\Models\Activities;
use App\Models\Contract;
use App\Models\DetailProduct;
use App\Models\Invoice;
use App\Models\Machine;
use App\Models\Pic;
use App\Models\ProductIn;
use App\Models\Quotation;
use App\Models\Reports;
use App\Models\SalesReports;
use App\Models\SerialProduct;
use App\Models\User;
use Carbon\Carbon;
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
    // Route::get('/overview', [DashboardController::class, 'overviewIndex']);

    // Route For Customers
    Route::resource('/customers', CustomersController::class);
    Route::get('/customers/detail/{id}', [CustomersController::class, 'show'])->name('detail.customers');

    // Route For Leads
    Route::resource('/leads', LeadsController::class);
    Route::get('/leads/detail/{id}', [LeadsController::class, 'show'])->name('detail.leads');
    Route::post('/leads/action/{id}', [LeadsController::class, 'storeActionWithLeads'])->name('action.leads');
    Route::post('/leads/visit/{id}', [LeadsController::class, 'storeVisitWithLeads'])->name('visit.leads');
    Route::post('/leads/convert/{id}', [LeadsController::class, 'convertToCustomers'])->name('convert.leads');

    // Route untuk Quotation
    Route::resource('/quotation', QuotationController::class);
    Route::get('/quotation/leads/create', [QuotationController::class, 'create'])->name('create.quotation');
    Route::get('/po', [QuotationController::class, 'po_quote'])->name('quotation.po');
    Route::get('/loss', [QuotationController::class, 'loss_quote'])->name('quotation.loss');
    Route::get('/quotation/{id}/change_status', [QuotationController::class, 'change_status'])->name('status.change.quotation');
    Route::post('/quotation/{id}/convert_flag', [QuotationController::class, 'convert_flag'])->name('convert-flag.quotation');
    Route::post('/quotation/{id}/convert_po', [QuotationController::class, 'convert_po'])->name('convert-po.quotation');
    Route::post('/quotation/{id}/upload_po', [QuotationController::class, 'upload_po'])->name('upload-po.quotation');
    Route::get('/quotation/{id}/download_po', [QuotationController::class, 'download_po'])->name('download-po.quotation');
    Route::delete('/quotation/{id}/delete_po', [QuotationController::class, 'delete_po'])->name('delete-po.quotation');
    Route::post('/quotation/{id}/insert_fee', [QuotationController::class, 'insert_fee'])->name('insert_fee.quotation');
    Route::post('/quotation/{id}/delete_fee', [QuotationController::class, 'delete_fee'])->name('delete_fee.quotation');
    Route::get('/quotation/revision/{id}', [QuotationController::class, 'edit_revisi'])->name('revisi.quotation');
    Route::get('/quotation/print/{id}', [QuotationController::class, 'print_quote'])->name('print.quotation');
    Route::get('/quotation/pdf/{id}', [QuotationController::class, 'pdf_quote'])->name('pdf.quotation');
    Route::get('/quotation/sales/{id}', [QuotationController::class, 'sales_quotation'])->name('sales.quotation');
    Route::get('/po/sales/{id}', [QuotationController::class, 'sales_po'])->name('sales.po');
    Route::get('/quotation/product/{id}', function ($id) {
        $product = SerialProduct::join('product as p', 'p.id', '=', 'serial_product.id_product')->where('serial_product.id', $id)->get(['p.description AS detail', 'serial_product.price']);
        return response()->json($product);
    });

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

    // Route untuk Overview
    Route::resource('/overview', OverviewController::class);
    Route::get('/overview/sales/{id}', [OverviewController::class, 'semesterOverviewSales'])->name('overview.semester');
    Route::get('/overview/{semester}/{sales}', [OverviewController::class, 'overviewAdmin'])->name('overview-sales.semester');
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
    Route::patch('/product/replacement/update/{id}', [ProductController::class, 'updateReplacement'])->name('product.replacement.update');
    Route::patch('/product/equivalent/update/{id}', [ProductController::class, 'updateEquivalent'])->name('product.equivalent.update');
    Route::delete('/product/equivalent/{id}', [ProductController::class, 'destroyEquivalent'])->name('product.equivalent.destroy');
    Route::delete('/product/replacement/{id}', [ProductController::class, 'destroyReplacement'])->name('product.replacement.destroy');
    Route::get('/master/product', [ProductController::class, 'indexMaster'])->name('master.product');

    // Route untuk Product In
    Route::resource('/product-in', ProductInController::class);
    Route::get('/product-in/print/{id}', [ProductInController::class, 'productIn_print'])->name('productIn.print');
    Route::get('/product-in/replacement/{id}', function ($id) {
        $product = DetailProduct::where('id_product', $id)->get();
        return response()->json($product);
    });
    Route::get('/product-out/replacement/{id}', function ($id) {
        $product = DetailProduct::findOrFail($id);
        return response()->json($product);
    });
    Route::get('/product-in/equivalent/{id}', function ($id) {
        $product = SerialProduct::where('id_product', $id)->get();
        return response()->json($product);
    });

    // Route untuk Product Out
    Route::resource('/product-out', ProductOutController::class);

    // Route untuk Stock
    Route::resource('/stock', StockController::class);

    // Route untuk report
    Route::resource('/sale-report', SalesReportController::class);
    Route::get('/sale-report/online/{id}', [SalesReportController::class, 'detailOnline'])->name('reports.online');
    Route::get('/sale-report/offline/{id}', [SalesReportController::class, 'detailOffline'])->name('reports.offline');

    // Route untuk Employee
    Route::resource('/employee', EmployeeController::class);
    Route::post('/employee/position/{id}', [EmployeeController::class, 'newPosition'])->name('new.position');
    Route::patch('/employee/target/{id}', [EmployeeController::class, 'updateTarget'])->name('update.target');

    // Route untuk machine
    Route::resource('/machine', MachineController::class);
    Route::post('/machine/technician/store', [MachineController::class, 'storeTechnician'])->name('store.machine-technician');
    Route::get('/machine/dropdown/{id}', function ($id) {
        $machine = Machine::where('id_client', $id)->get();
        return response()->json($machine);
    });

    // Route untuk Selling Contract dan Confirm Order
    Route::resource('/contract', ContractController::class);
    Route::post('/contract/selling-contract/{id}', [ContractController::class, 'create_selling_contract'])->name('selling.contract');
    Route::post('/contract/confirm-order/{id}', [ContractController::class, 'create_confirm_order'])->name('confirm.order');
    Route::post('/request/selling-contract/{id}', [ContractController::class, 'request_selling_contract'])->name('request.selling');
    Route::post('/request/confirm-order/{id}', [ContractController::class, 'request_confirm_order'])->name('request.order');
    Route::get('/contract/print/{id}', [ContractController::class, 'contract_print'])->name('contract.print');
    Route::get('/selling/contract', [ContractController::class, 'index_selling'])->name('index.selling');
    Route::get('/order/contract', [ContractController::class, 'index_order'])->name('index.order');
    Route::post('/contract/accept/{id}', [ContractController::class, 'accept_contract'])->name('accept.contract');
    Route::get('/db/selling-contract/tax', function () {
        $contract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.type', 'Selling')
            ->where('contract.level', '1')
            ->where('q.tax', '11')
            ->get([
                'contract.*',
                'q.harga_total',
                'u.name',
                'c.company'
            ]);
        return response()->json(['data' => $contract]);
    });

    Route::resource('/invoice', InvoiceController::class);
    Route::get('/request/invoice/{id}', [InvoiceController::class, 'before_accept'])->name('before.accept');
    Route::get('/request/invoice', [InvoiceController::class, 'request'])->name('invoice.request');
    Route::get('/print/invoice/{id}', [InvoiceController::class, 'print_invoice'])->name('print.invoice');

    Route::get('/db/selling-contract/non-tax', function () {
        $contract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.type', 'Selling')
            ->where('contract.level', '1')
            ->where('q.tax', '0')
            ->get([
                'contract.*',
                'q.harga_total',
                'u.name',
                'c.company'
            ]);
        return response()->json(['data' => $contract]);
    });
    Route::get('/db/confirm-order/tax', function () {
        $contract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.type', 'Order')
            ->where('contract.level', '1')
            ->where('q.tax', '11')
            ->get([
                'contract.*',
                'q.harga_total',
                'u.name',
                'c.company'
            ]);
        return response()->json(['data' => $contract]);
    });
    Route::get('/db/confirm-order/non-tax', function () {
        $contract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.type', 'Order')
            ->where('contract.level', '1')
            ->where('q.tax', '0')
            ->get([
                'contract.*',
                'q.harga_total',
                'u.name',
                'c.company'
            ]);
        return response()->json(['data' => $contract]);
    });
    Route::get('/db/request-contract', function () {
        $contract = Contract::join('quotation as q', 'q.id', '=', 'contract.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('contract.level', '0')
            ->get([
                'contract.*',
                'q.harga_total',
                'u.name',
                'c.company'
            ]);
        return response()->json(['data' => $contract]);
    });

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
    Route::get('/db/quotation/admin', function () {
        require_once base_path('app/api/quotation/connectionAdmin.php');
    });
    Route::get('/db/quotation/sales/{id}', function ($id) {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->join('client', 'client.id', '=', 'pic.id_client')->whereMonth('estimated_date', $monthNow)->where('quotation.id_sales', $id)->get(['quotation.*', 'client.company']);
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/po/sales/{id}', function ($id) {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->join('client', 'client.id', '=', 'pic.id_client')->whereMonth('po_date', $monthNow)->where('quotation.id_sales', $id)->where('status', '100')->orderBy('po_date', 'asc')->get(['quotation.*', 'client.company']);
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/quotation/invoice', function () {
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNull('invoice.no_invoice')
            ->get(['quotation.*', 'client.company', 'users.name']);
        ;
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/invoice', function () {
        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->get(['invoice.*', 'client.company', 'users.name', 'quotation.harga_total', 'quotation.po_date']);
        ;
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/po', function () {
        require_once base_path('app/api/po/connection.php');
    });
    Route::get('/db/po/admin', function () {
        require_once base_path('app/api/po/connectionAdmin.php');
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
    Route::get('/db/loss/admin', function () {
        require_once base_path('app/api/lossQ/connectionAdmin.php');
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
    Route::get('/db/product/master', function () {
        require_once base_path('app/api/product/master/connection.php');
    });
    Route::get('/db/product/stock', function () {
        require_once base_path('app/api/product/stock/connection.php');
    });
    Route::get('/db/product/serial/{id}', function ($id) {
        // Menggunakan Eloquent untuk mengambil data serial_product berdasarkan id
        $serialProduct = SerialProduct::where('id_product', $id)->join('product', 'product.id', '=', 'serial_product.id_product')->get(['serial_product.*', 'product.id AS idp']);
        // Mengembalikan data dalam bentuk JSON
        return response()->json(['data' => $serialProduct]);
    });
    Route::get('/db/product/quotation/{id}', function ($id) {
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->where('pic.id_client', $id)->get('quotation.*');
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/product/in/detail/{id}', function ($id) {
        $products = DB::table('product_in as p')
            ->select('p.*', 'dp.replacement', 'd.qty')
            ->leftJoin('detail_product_in as d', 'd.id_product_in', '=', 'p.id')
            ->leftJoin('detail_product as dp', 'd.id_detail_product', '=', 'dp.id')
            ->leftJoin('product as pr', 'dp.id_product', '=', 'pr.id')
            ->where('pr.id', $id)
            ->get();
        return response()->json(['data' => $products]);
    });
    Route::get('/db/product/out/detail/{id}', function ($id) {
        $products = DB::table('product_out as p')
            ->select('p.*', 'dp.replacement', 'd.qty')
            ->leftJoin('detail_product_out as d', 'd.id_product_out', '=', 'p.id')
            ->leftJoin('detail_product as dp', 'd.id_detail_product', '=', 'dp.id')
            ->leftJoin('product as pr', 'dp.id_product', '=', 'pr.id')
            ->where('pr.id', $id)
            ->get();
        return response()->json(['data' => $products]);
    });

    Route::get('/db/sales/overview/{id}', function ($id) {
        $sales = User::find($id);
        $data = DB::table('sales_reports AS s')
            ->select('s.*', DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
        JOIN users AS u ON q.id_sales = u.id
        WHERE MONTH(q.po_date) BETWEEN 
            CASE 
                WHEN s.semester = "1" THEN 1 
                WHEN s.semester = "2" THEN 7 
            END 
        AND 
            CASE 
                WHEN s.semester = "1" THEN 6 
                WHEN s.semester = "2" THEN 12 
            END
        AND YEAR(q.po_date) = s.year
        AND u.id = ' . $sales->id . ') AS total'), DB::raw('(SELECT COALESCE(SUM(q.total_no_tax), 0) FROM quotation AS q 
        JOIN users AS u ON q.id_sales = u.id
        WHERE MONTH(q.po_date) BETWEEN 
            CASE 
                WHEN s.semester = "1" THEN 1 
                WHEN s.semester = "2" THEN 7 
            END 
        AND 
            CASE 
                WHEN s.semester = "1" THEN 6 
                WHEN s.semester = "2" THEN 12 
            END
        AND YEAR(q.po_date) = s.year
        AND u.id = ' . $sales->id . ') AS price'), DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
        JOIN users AS u ON q.id_sales = u.id
        WHERE MONTH(q.estimated_date) BETWEEN 
            CASE 
                WHEN s.semester = "1" THEN 1 
                WHEN s.semester = "2" THEN 7 
            END 
        AND 
            CASE 
                WHEN s.semester = "1" THEN 6 
                WHEN s.semester = "2" THEN 12 
            END
        AND YEAR(q.estimated_date) = s.year
        AND u.id = ' . $sales->id . ') AS quote'))
            ->get();
        return response()->json(['data' => $data]);
    });

    Route::get('/db/client/po-history/{id}', function ($id) {
        $data = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->where('quotation.status', '100')->where('pic.id_client', $id)->get('quotation.*');
        return response()->json(['data' => $data]);
    });
    Route::get('/db/client/crm-history/{id}', function ($id) {
        $data = Activities::where('id_client', $id)->whereIn('name', ['Daily Call', 'Follow Up', 'CRM'])->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/client/service-history/{id}', function ($id) {
        $data = Reports::join('pic', 'pic.id', '=', 'reports.id_pic')
            ->join('users', 'users.id', '=', 'reports.id_technician')
            ->join('machine', 'machine.id', '=', 'reports.id_machine')
            ->where('pic.id_client', $id)
            ->where('reports.type', 'Service')
            ->select(
                'reports.*',
                'users.name',
                DB::raw("CONCAT(machine.brand, ' ', machine.type) AS brand_type")
            )
            ->groupBy('reports.id')
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/client/visit-history/{id}', function ($id) {
        $data = Reports::join('pic', 'pic.id', '=', 'reports.id_pic')
            ->join('users', 'users.id', '=', 'reports.id_technician')
            ->join('machine', 'machine.id', '=', 'reports.id_machine')
            ->where('pic.id_client', $id)
            ->where('reports.type', 'Visit')
            ->select(
                'reports.*',
                'users.name',
                DB::raw("CONCAT(machine.brand, ' ', machine.type) AS brand_type")
            )
            ->groupBy('reports.id')
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/client/general-history/{id}', function ($id) {
        $data = Reports::join('pic', 'pic.id', '=', 'reports.id_pic')
            ->join('users', 'users.id', '=', 'reports.id_technician')
            ->join('machine', 'machine.id', '=', 'reports.id_machine')
            ->where('pic.id_client', $id)
            ->where('reports.type', 'General')
            ->select(
                'reports.*',
                'users.name',
                DB::raw("CONCAT(machine.brand, ' ', machine.type) AS brand_type")
            )
            ->groupBy('reports.id')
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/productIn', function () {
        require_once base_path('app/api/product/in/connection.php');
    });
    Route::get('/db/productInTax', function () {
        require_once base_path('app/api/product/in/connectionTax.php');
    });
    Route::get('/db/productInNoTax', function () {
        require_once base_path('app/api/product/in/connectionNoTax.php');
    });
    Route::get('/db/productOut', function () {
        require_once base_path('app/api/product/out/connection.php');
    });
    Route::get('/db/product/sales', function () {
        require_once base_path('app/api/product/connectionSales.php');
    });
    Route::get('/db/user', function () {
        require_once base_path('app/api/user/connection.php');
    });
    Route::get('/db/sales/overview', function () {
        $sales = DB::table('sales_reports AS s')
            ->select('s.*', DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
        JOIN users AS u ON q.id_sales = u.id
        WHERE MONTH(q.po_date) BETWEEN 
            CASE 
                WHEN s.semester = "1" THEN 1 
                WHEN s.semester = "2" THEN 7 
            END 
        AND 
            CASE 
                WHEN s.semester = "1" THEN 6 
                WHEN s.semester = "2" THEN 12 
            END
        AND YEAR(q.po_date) = s.year
        AND u.id = ' . Auth::user()->id . ') AS total'), DB::raw('(SELECT COALESCE(SUM(q.total_no_tax), 0) FROM quotation AS q 
        JOIN users AS u ON q.id_sales = u.id
        WHERE MONTH(q.po_date) BETWEEN 
            CASE 
                WHEN s.semester = "1" THEN 1 
                WHEN s.semester = "2" THEN 7 
            END 
        AND 
            CASE 
                WHEN s.semester = "1" THEN 6 
                WHEN s.semester = "2" THEN 12 
            END
        AND YEAR(q.po_date) = s.year
        AND u.id = ' . Auth::user()->id . ') AS price'), DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
        JOIN users AS u ON q.id_sales = u.id
        WHERE MONTH(q.estimated_date) BETWEEN 
            CASE 
                WHEN s.semester = "1" THEN 1 
                WHEN s.semester = "2" THEN 7 
            END 
        AND 
            CASE 
                WHEN s.semester = "1" THEN 6 
                WHEN s.semester = "2" THEN 12 
            END
        AND YEAR(q.estimated_date) = s.year
        AND u.id = ' . Auth::user()->id . ') AS quote'))
            ->get();
        return response()->json(['data' => $sales]);
    });

    Route::get('/db/sales/reports/online', function () {
        // $reports = SalesReports::orderBy('id', 'ASC')->get();
        $reports = DB::table('sales_reports AS s')
            ->select('s.*', DB::raw('
        (SELECT COALESCE(SUM(dpo.qty), 0) 
            FROM product_out AS po
            JOIN detail_product_out AS dpo ON dpo.id_product_out = po.id 
            WHERE 
                MONTH(po.date) >= 
                    CASE 
                        WHEN s.semester = "1" THEN 1 
                        WHEN s.semester = "2" THEN 7 
                    END 
                AND 
                MONTH(po.date) <= 
                    CASE 
                        WHEN s.semester = "1" THEN 6 
                        WHEN s.semester = "2" THEN 12 
                    END
                AND YEAR(po.date) = s.year
                AND po.vers = "Online"
        ) AS total'))
            ->get();
        return response()->json(['data' => $reports]);
    });
    Route::get('/db/sales/reports/offline', function () {
        // $reports = SalesReports::orderBy('id', 'ASC')->get();
        $reports = DB::table('sales_reports AS s')
            ->select('s.*', DB::raw('
        (SELECT COALESCE(SUM(dpo.qty), 0) 
            FROM product_out AS po
            JOIN detail_product_out AS dpo ON dpo.id_product_out = po.id 
            WHERE 
                MONTH(po.date) >= 
                    CASE 
                        WHEN s.semester = "1" THEN 1 
                        WHEN s.semester = "2" THEN 7 
                    END 
                AND 
                MONTH(po.date) <= 
                    CASE 
                        WHEN s.semester = "1" THEN 6 
                        WHEN s.semester = "2" THEN 12 
                    END
                AND YEAR(po.date) = s.year
                AND po.vers = "Offline"
        ) AS total'))
            ->get();
        return response()->json(['data' => $reports]);
    });

    Route::get('/db/sales/reports/online/{id}', function ($id) {
        $report = SalesReports::find($id);

        if ($report->semester == '1') {
            // $product = DB::table('serial_product AS s')
            // ->select('s.id', 'r.commodity', 's.pn', DB::raw('COALESCE(SUM(d.qty), 0) AS total'))
            // ->leftJoin('detail_product_out AS d', 'd.id_serial_product', '=', 's.id')
            // ->leftJoin('product_out AS p', function ($join) use ($report) {
            //     $join->on('p.id', '=', 'd.id_product_out')
            //         ->whereYear('p.date', $report->year)
            //         ->whereMonth('p.date', '>=', '1')
            //         ->whereMonth('p.date', '<=', '6');
            // })
            // ->leftJoin('product AS r', 's.id_product', '=', 'r.id')
            // ->groupBy('s.id', 'r.commodity', 's.pn')
            // ->get();
            $product = DB::table('detail_product_out AS d')
                ->select('d.id', 'r.commodity', 's.fxp_parts', 'd.price', 's.pn', DB::raw('SUM(d.qty) AS total'))
                ->leftJoin('product_out AS p', 'd.id_product_out', '=', 'p.id')
                ->leftJoin('serial_product AS s', 'd.id_serial_product', '=', 's.id')
                ->leftJoin('product AS r', 's.id_product', '=', 'r.id')
                ->whereYear('p.date', $report->year)
                ->whereMonth('p.date', '>=', '1')
                ->whereMonth('p.date', '<=', '6')
                ->where('p.vers', 'Online')
                ->groupBy('d.id_serial_product')
                ->get();
        } elseif ($report->semester == '2') {
            $product = DB::table('detail_product_out AS d')
                ->select('d.id', 'r.commodity', 's.fxp_parts', 'd.price', DB::raw('SUM(d.qty) AS total'))
                ->leftJoin('product_out AS p', 'd.id_product_out', '=', 'p.id')
                ->leftJoin('serial_product AS s', 'd.id_serial_product', '=', 's.id')
                ->leftJoin('product AS r', 's.id_product', '=', 'r.id')
                ->whereYear('p.date', $report->year)
                ->whereMonth('p.date', '>=', '7')
                ->whereMonth('p.date', '<=', '12')
                ->where('p.vers', 'Online')
                ->groupBy('d.id_serial_product')
                ->get();
        }
        return response()->json(['data' => $product]);
    });

    Route::get('/db/sales/reports/offline/{id}', function ($id) {
        $report = SalesReports::find($id);

        if ($report->semester == '1') {
            // $product = DB::table('serial_product AS s')
            // ->select('s.id', 'r.commodity', 's.pn', DB::raw('COALESCE(SUM(d.qty), 0) AS total'))
            // ->leftJoin('detail_product_out AS d', 'd.id_serial_product', '=', 's.id')
            // ->leftJoin('product_out AS p', function ($join) use ($report) {
            //     $join->on('p.id', '=', 'd.id_product_out')
            //         ->whereYear('p.date', $report->year)
            //         ->whereMonth('p.date', '>=', '1')
            //         ->whereMonth('p.date', '<=', '6');
            // })
            // ->leftJoin('product AS r', 's.id_product', '=', 'r.id')
            // ->groupBy('s.id', 'r.commodity', 's.pn')
            // ->get();
            $product = DB::table('detail_product_out AS d')
                ->select('d.id', 'r.commodity', 's.fxp_parts', 'd.price', 's.pn', DB::raw('SUM(d.qty) AS total'))
                ->leftJoin('product_out AS p', 'd.id_product_out', '=', 'p.id')
                ->leftJoin('serial_product AS s', 'd.id_serial_product', '=', 's.id')
                ->leftJoin('product AS r', 's.id_product', '=', 'r.id')
                ->whereYear('p.date', $report->year)
                ->whereMonth('p.date', '>=', '1')
                ->whereMonth('p.date', '<=', '6')
                ->where('p.vers', 'Offline')
                ->groupBy('d.id_serial_product')
                ->get();
        } elseif ($report->semester == '2') {
            $product = DB::table('detail_product_out AS d')
                ->select('d.id', 'r.commodity', 's.fxp_parts', 'd.price', DB::raw('SUM(d.qty) AS total'))
                ->leftJoin('product_out AS p', 'd.id_product_out', '=', 'p.id')
                ->leftJoin('serial_product AS s', 'd.id_serial_product', '=', 's.id')
                ->leftJoin('product AS r', 's.id_product', '=', 'r.id')
                ->whereYear('p.date', $report->year)
                ->whereMonth('p.date', '>=', '7')
                ->whereMonth('p.date', '<=', '12')
                ->where('p.vers', 'Offline')
                ->groupBy('d.id_serial_product')
                ->get();
        }
        return response()->json(['data' => $product]);
    });
});
Auth::routes();
