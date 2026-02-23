<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\ApiTableController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\AuditController;
use App\Http\Controllers\ChangeWarehouseController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExistingController;
use App\Http\Controllers\FixedController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\MonitoringClientController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\NotulenController;
use App\Http\Controllers\OpnameController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PayableController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PendingController;
use App\Http\Controllers\PicController;
use App\Http\Controllers\POController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductInController;
use App\Http\Controllers\ProductOutController;
use App\Http\Controllers\ProductSetController;
use App\Http\Controllers\ProspectController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReqVisitController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\SalesOnlineController;
use App\Http\Controllers\SalesReportController;
use App\Http\Controllers\ServiceReportsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WatermarkController;
use App\Models\Account;
use App\Models\Activities;
use App\Models\ChangeWarehouse;
use App\Models\Client;
use App\Models\Comment;
use App\Models\Contract;
use App\Models\DetailProduct;
use App\Models\DetailStockOpname;
use App\Models\FixedAsset;
use App\Models\Invoice;
use App\Models\Issues;
use App\Models\LabaRugi;
use App\Models\Library;
use App\Models\Machine;
use App\Models\MachineTemplate;
use App\Models\Mainlog;
use App\Models\Monitoring;
use App\Models\MonitoringWeekly;
use App\Models\Notulen;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PendingPO;
use App\Models\Pic;
use App\Models\Product;
use App\Models\ProductIn;
use App\Models\ProductSet;
use App\Models\Prospect;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use App\Models\Reports;
use App\Models\ReturnQ;
use App\Models\SalesReports;
use App\Models\SerialProduct;
use App\Models\Service;
use App\Models\StatusMonitoring;
use App\Models\StockOpname;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use FontLib\Table\Type\post;
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
Route::get('/monitoring/button/{id}', [MonitoringController::class, 'button'])->name('button.monitoring');
Route::get('/monitoring/daily-visit/{id}', [MonitoringController::class, 'visitorDaily'])->name('visitor.daily-monitoring');
Route::get('/monitoring/kosongan', [MonitoringController::class, 'kosongan'])->name('kosongan.monitoring');
Route::get('/monitoring-daily-visit/{id}/{month}', [MonitoringController::class, 'visitorDailyMonth'])->name('visitor-change.daily-monitoring');
Route::get('/monitoring/daily-log/{id}', [MonitoringController::class, 'logDaily'])->name('log.daily-monitoring');
Route::get('/monitoring/weekly-visit/{id}', [MonitoringController::class, 'visitorWeekly'])->name('visitor.weekly-monitoring');
Route::get('/service-manager/recap-today', [MonitoringController::class, 'recapClient'])->name('service-manager.recap-monitoring-today');
Route::get('/service-manager/recap/{date?}', [MonitoringController::class, 'recapM'])->name('service-manager.recap-monitoring');
Route::get('/recap-week/{week}/{date?}', [MonitoringController::class, 'recapMW'])->name('service-manager.recap-monitoring-week');
Route::get('/db/machine-monitoring-visit/{id}', [MonitoringController::class, 'getMonitoringCompressorThisMonth']);
Route::get('/db/dryer-monitoring-visit/{id}', [MonitoringController::class, 'getMonitoringDryerThisMonth']);

Route::get('/service-reports/print/{id}', [ServiceReportsController::class, 'print_reports'])->name('service-reports.print');

Route::get('/watermark/index', [WatermarkController::class, 'index'])->name('watermark.index');
Route::post('/watermark/upload', [WatermarkController::class, 'upload'])->name('watermark.upload');
Route::get('/watermark/download', [WatermarkController::class, 'downloadAll'])->name('watermark.download');
Route::post('/watermark/reset', [WatermarkController::class, 'reset'])->name('watermark.reset');

Route::get('/existing/yearly/{id}', [CrmController::class, 'detailPerYear'])->name('existing.yearly');

Route::group(["middleware" => "auth"], function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('check.expired')->name('dashboard');


    Route::get('/under-maintenance', function () {
        return view('pages.under-maintenance');
    })->name('under-maintenance');
    // Route User
    Route::resource('/profile', UserController::class);
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
    Route::get('/prospect-quotation', [QuotationController::class, 'prospect_quote'])->name('quotation.prospect');
    Route::get('/po', [QuotationController::class, 'po_quote'])->name('quotation.po');
    Route::get('/loss', [QuotationController::class, 'loss_quote'])->name('quotation.loss');
    Route::get('/quotation/{id}/change_status', [QuotationController::class, 'change_status'])->name('status.change.quotation');
    Route::post('/quotation/{id}/add_comment', [QuotationController::class, 'add_comment'])->name('add-comment.quotation');
    Route::post('/quotation/{id}/view_comment', [QuotationController::class, 'view_comment'])->name('view-comment.quotation');
    Route::post('/quotation/{id}/change_po', [QuotationController::class, 'change_po'])->name('change-po.quotation');
    Route::post('/quotation/{id}/cancel_po', [QuotationController::class, 'cancel_po'])->name('status.cancel.quotation');
    Route::post('/quotation/{id}/convert_flag', [QuotationController::class, 'convert_flag'])->name('convert-flag.quotation');
    Route::post('/quotation/{id}/convert_po', [QuotationController::class, 'convert_po'])->name('convert-po.quotation');
    Route::post('/quotation/{id}/request_bp', [QuotationController::class, 'request_bp'])->name('request-bp.quotation');
    Route::post('/quotation/{id}/upload_po', [QuotationController::class, 'upload_po'])->name('upload-po.quotation');
    Route::post('/quotation/{id}/mentions', [QuotationController::class, 'add_mention'])->name('add_mention.quotation');
    Route::get('/quotation/{id}/download_po', [QuotationController::class, 'download_po'])->name('download-po.quotation');
    Route::delete('/quotation/{id}/delete_po', [QuotationController::class, 'delete_po'])->name('delete-po.quotation');
    Route::post('/quotation/{id}/insert_fee', [QuotationController::class, 'insert_fee'])->name('insert_fee.quotation');
    Route::post('/quotation/{id}/delete_fee', [QuotationController::class, 'delete_fee'])->name('delete_fee.quotation');
    Route::post('/quotation/{id}/add_payment', [QuotationController::class, 'add_payment'])->name('add_payment.quotation');
    Route::post('/quotation/{id}/proof_payment', [QuotationController::class, 'proof_payment'])->name('proof_payment.quotation');
    Route::post('/quotation/{id}/change_primary', [QuotationController::class, 'change_primary'])->name('change_primary.quotation');
    Route::get('/quotation/{id}/download_payment', [QuotationController::class, 'download_payment'])->name('download-payment.quotation');
    Route::delete('/quotation/{id}/delete_payment', [QuotationController::class, 'delete_payment'])->name('delete-payment.quotation');
    Route::post('/quotation/{id}/confirm_payment', [QuotationController::class, 'confirm_payment'])->name('confirm-payment.quotation');
    Route::get('/quotation/revision/{id}', [QuotationController::class, 'edit_revisi'])->name('revisi.quotation');
    Route::get('/quotation/edit-sparepart/{id}', [QuotationController::class, 'edit_parts'])->name('edit-sparepart.quotation');
    Route::get('/quotation/edit-service/{id}', [QuotationController::class, 'edit_service'])->name('edit-service.quotation');
    Route::patch('/quotation/edit-sparepart/{id}', [QuotationController::class, 'update_part'])->name('update-sparepart.quotation');
    Route::patch('/quotation/edit-service/{id}', [QuotationController::class, 'update_service'])->name('edit-service.quotation');
    Route::get('/quotation/revision-overhaul/{id}', [QuotationController::class, 'revisionService'])->name('revisi-overhaul.quotation');
    Route::get('/quotation/print/{id}', [QuotationController::class, 'print_quote'])->name('print.quotation');
    Route::get('/quotation/pdf/{id}', [QuotationController::class, 'pdf_quote'])->name('pdf.quotation');
    Route::get('/quotation/sales/{id}', [QuotationController::class, 'sales_quotation'])->name('sales.quotation');
    Route::get('/po/sales/{id}', [QuotationController::class, 'sales_po'])->name('sales.po');
    Route::get('/quotation/sparepart/{id}', [QuotationController::class, 'replacementDetailSparepart'])->name('detail.replacement');
    Route::get('/quotation/unit/{id}', [QuotationController::class, 'replacementDetailUnit'])->name('detail.replacement');
    Route::get('/quotation/client/{id}', function ($id) {
        $client = Client::find($id);
        return response()->json($client);
    });
    Route::get('/quotation/pic/{id}', function ($id) {
        $client = Pic::where('pic.id_client', $id)->get();
        return response()->json($client);
    });
    Route::get('/quote/unit', [QuotationController::class, 'quotationUnit'])->name('index-unit.quotation');
    Route::get('/quote/unit/create', [QuotationController::class, 'quotationCreateUnit'])->name('create-unit.quotation');
    Route::get('/quote/unit-detail/{id}', [UnitController::class, 'quotationDetail'])->name('detail.quote-unit');
    Route::get('/quote/service/create', [QuotationController::class, 'createService'])->name('create-service.quotation');
    Route::post('/quote/service/store', [QuotationController::class, 'storeService'])->name('store-service.quotation');
    Route::get('/quote/service-show/{id}', [QuotationController::class, 'showService'])->name('show-service.quotation');
    Route::post('/quote/service-update/{id}', [QuotationController::class, 'updateService'])->name('update-service.quotation');
    Route::delete('/quote/service-delete/{id}', [QuotationController::class, 'destroyService'])->name('delete-service.quotation');
    Route::get('/quote/service-print/{id}', [QuotationController::class, 'printService'])->name('service-print.quotation');
    Route::get('/quote/service-print-no-image/{id}', [QuotationController::class, 'printNoImageService'])->name('service-print-no-image.quotation');

    Route::post('/quote/choose-machine', [QuotationController::class, 'chooseMachine'])->name('quotation.choose-machine');
    Route::post('/quote/overhaul/store', [QuotationController::class, 'storeOverhaul'])->name('store-overhaul.quotation');
    Route::get('/quote/overhaul-create/{id}', [QuotationController::class, 'createOverhaul'])->name('create-overhaul.quotation');
    Route::get('/quote/overhaul-show/{id}', [QuotationController::class, 'showOverhaul'])->name('show-overhaul.quotation');
    Route::get('/quote/overhaul-print/{id}', [QuotationController::class, 'printOverhaul'])->name('overhaul-print.quotation');
    Route::get('/quote/overhaul-print-no-image/{id}', [QuotationController::class, 'printNoImageOverhaul'])->name('overhaul-print-no-image.quotation');
    Route::get('/quote/overhaul-revision/{id}', [QuotationController::class, 'revisionOverhaul'])->name('overhaul-revision.quotation');
    Route::post('/quote/overhaul-update/{id}', [QuotationController::class, 'updateOverhaul'])->name('overhaul-update.quotation');

    // Route untuk Visit
    Route::get('/visits/leads', function () {
        return view('pages.sales.visits.index');
    });

    // Route untuk existing
    Route::resource('/existing', CrmController::class);
    Route::get('/existing-bangkrupt', [CrmController::class, 'indexBangkrupt'])->name('index.bangkrupt');
    Route::post('/existing/action/{id}', [CrmController::class, 'storeActionWithCrm'])->name('action.crm');
    Route::post('/existing/update-status/{id}', [CrmController::class, 'updateStatusAtDropdown'])->name('update-status.crm');
    Route::get('/ru', [CrmController::class, 'ruIndex'])->name('ru.index');

    // Route untuk service Reports
    Route::resource('/service-reports', ServiceReportsController::class);
    Route::post('/service-reports/sign/{id}', [ServiceReportsController::class, 'hand_sign'])->name('service-reports.sign');
    Route::post('/service-reports/image/{id}', [ServiceReportsController::class, 'inputImage'])->name('service-reports.image');
    Route::delete('/service-reports/del-sign/{id}', [ServiceReportsController::class, 'delete_hand_sign'])->name('service-reports.del-sign');
    Route::delete('/service-reports/del-image/{id}', [ServiceReportsController::class, 'deleteImage'])->name('service-reports.del-image');
    Route::post('/service-reports-viewed', [ServiceReportsController::class, 'markViewed']);
    Route::get('/service-reports-servicem', [ServiceReportsController::class, 'serviceMer'])->name('service-reports.manager');

    // Route untuk audit
    Route::resource('/audit-tools', AuditController::class);
    Route::get('/audit-tools/print/{id}', [AuditController::class, 'print_audit'])->name('audit-tools.print');

    // Route untuk Overview
    Route::resource('/overview', OverviewController::class);
    Route::get('/overview/sales/{id}', [OverviewController::class, 'semesterOverviewSales'])->name('overview.semester');
    Route::get('/detail-overview/{sales}/{date}', [OverviewController::class, 'detailSemesterOverview'])->name('detail-overview.semester');
    Route::get('/overview/{semester}/{sales}', [OverviewController::class, 'overviewAdmin'])->name('overview-sales.semester');
    Route::get('/report/{semester}', [OverviewController::class, 'reportsSemester'])->name('report.semester');
    // Route untuk PO
    // Route::get('/pending-po', function () {
    //     return view('pages.sales.po.pending.index');
    // });

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
    Route::get('/master/product', [ProductController::class, 'indexMaster'])->name(name: 'master.product');

    // Route untuk unit
    Route::resource('/unit', UnitController::class);
    Route::get('/unit-global', [UnitController::class, 'indexGlobal'])->name('unit-global.index');
    Route::post('/unit-global', [UnitController::class, 'storeGlobal'])->name('unit-global.store');
    Route::post('/store/sparepart/{id}', [UnitController::class, 'storeSparepart'])->name('unit-sparepart.store');
    Route::delete('/delete/sparepart/{id}', [UnitController::class, 'deleteSparepart'])->name('unit-sparepart.delete');
    Route::patch('/unit-reftech/{id}', [UnitController::class, 'updateUnitReftech'])->name('unit-reftech.edit');
    Route::get('/unit-global/{id}', [UnitController::class, 'showGlobal'])->name('unit-global.show');
    Route::get('/cor-factor/calculator', [UnitController::class, 'corfac'])->name('calculator.correction');

    // Route untuk Product In
    Route::resource('/product-in', ProductInController::class);
    Route::get('/product-in/print/{id}', [ProductInController::class, 'productIn_print'])->name('productIn.print');
    Route::get('/product-in/replacement/{id}', function ($id) {
        $product = DetailProduct::where('id_product', $id)->join('product as p', 'p.id', '=', 'detail_product.id_product')->get(['detail_product.*', 'p.weight', 'p.stock as pStock']);
        return response()->json($product);
    });
    Route::post('/product-in/logistik', [ProductInController::class, 'logistic_store'])->name('product-in.logistic-store');
    Route::post('/product-in/invoicing/{id}', [ProductInController::class, 'invoicing'])->name('product-in.invoicing');
    Route::post('/product-in/accept/{id}', [ProductInController::class, 'acceptIn'])->name('product-in.accept');
    Route::post('/product-in/return/{id}', [ProductInController::class, 'return'])->name('product-in.return');
    Route::post('/product-in/clear-return/{id}', [ProductInController::class, 'clearReturn'])->name('product-in.clear-return');
    Route::get('/product-out/replacement/{id}', function ($id) {
        $product = DetailProduct::findOrFail($id);
        return response()->json($product);
    });
    Route::get('/product-in/equivalent/{id}', function ($id) {
        $product = SerialProduct::where('id_product', $id)->get();
        return response()->json($product);
    });
    Route::get('/supplier', [ProductInController::class, 'indexSupplier'])->name('supplier.index');
    Route::get('/supplier/{id}', [ProductInController::class, 'detailSupplier'])->name('supplier.detail');
    Route::post('/supplier', [ProductInController::class, 'storeSupplier'])->name('supplier.store');
    Route::patch('/supplier/{id}', [ProductInController::class, 'updateSupplier'])->name('supplier.update');
    Route::delete('/supplier/{id}', [ProductInController::class, 'deleteSupplier'])->name('supplier.delete');

    // Route untuk Product Out
    Route::resource('/product-out', ProductOutController::class);
    Route::get('/productout/index/invoice', [ProductOutController::class, 'index_invoice'])->name('product-out.index-invoice');
    Route::get('/productout/invoice/{id}', [ProductOutController::class, 'invoice'])->name('product-out.invoice');
    Route::post('/product-out/{id}/change_no', [ProductOutController::class, 'change_no'])->name('product-out.change_no');
    Route::get('/db/invoice/product-out', function () {
        $invoice = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
            ->leftJoin('product_out as p', 'p.invoice', '=', 'invoice.no_invoice')  // Menggunakan left join
            ->join('detail_quotation as dq', 'dq.id_quotation', '=', 'q.id')
            ->join('serial_product as s', 's.id', '=', 'dq.id_equivalent')
            ->join('pic', 'pic.id', '=', 'q.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->where('q.status', '100')
            ->whereNotNull('q.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->whereNull('p.id')  // Kondisi untuk mengecek invoice yang tidak memiliki product_out
            ->groupBy('invoice.id')  // Mengelompokkan hasil per invoice
            ->orderByDesc('invoice.no_invoice')
            ->get([
                'invoice.*',
                'client.company',
                'q.po_date',
                \DB::raw("GROUP_CONCAT(s.pn SEPARATOR ', ') as part_numbers")
            ]);
        return response()->json(['data' => $invoice]);
    });

    // Route untuk Stock
    Route::resource('/stock', StockController::class);
    Route::patch('/stock/unit/{id}', [StockController::class, 'updateUnit'])->name('update.stock-unit');

    // Route untuk report
    Route::resource('/sale-report', SalesReportController::class);
    Route::get('/sale-report/online/{id}', [SalesReportController::class, 'detailOnline'])->name('reports.online');
    Route::get('/sale-report/offline/{id}', [SalesReportController::class, 'detailOffline'])->name('reports.offline');
    Route::get('/sales-report/yearly/{year}', [SalesReportController::class, 'yearly'])->name('reports.yearly');

    // Route untuk Employee
    Route::resource('/employee', EmployeeController::class);
    Route::post('/employee/position/{id}', [EmployeeController::class, 'newPosition'])->name('new.position');
    Route::patch('/employee/target/{id}', [EmployeeController::class, 'updateTarget'])->name('update.target');

    // Route untuk machine
    Route::resource('/machine', MachineController::class);
    Route::post('/machine/technician/store', [MachineController::class, 'storeTechnician'])->name('store.machine-technician');
    Route::get('/machine/dropdown/{id}', function ($id) {
        $machine = Machine::join('client as c', 'c.id', '=', 'machine.id_client')
            ->join('pic as p', 'p.id_client', '=', 'c.id')
            ->join('serial_product as s', 's.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 's.id_product')
            ->where('p.id', $id)
            ->groupBy('machine.id', 'u.id')
            ->select(
                'machine.*',
                'u.bar',
                // 'u.voltage',
                'u.sku',
                's.brand',
            )
            ->get();
        return response()->json($machine);
    });
    Route::get('/client/dropdown/{id}', function ($id) {
        $client = Client::where('id_sales', $id)->get();
        return response()->json($client);
    });
    Route::get('/pic/dropdown/{id}', function ($id) {
        $pic = Pic::where('id_client', $id)->get();
        return response()->json($pic);
    });

    // Route Monitoring
    Route::get('/monitoring/daily/{id}', [MonitoringController::class, 'indexDaily'])->name('index.daily-monitoring');
    Route::get('/monitoring/daily-create/{id}', [MonitoringController::class, 'createDaily'])->name('create.daily-monitoring');
    Route::get('/monitoring-service-create/{monitoring}/{id}', [MonitoringController::class, 'createDailyReports'])->name('create.daily-monitoring-reports');
    Route::post('/monitoring-service-store/{monitoring}/{id}', [MonitoringController::class, 'storeService'])->name('store.daily-monitoring-reports');
    Route::post('/monitoring/daily-store/{id}', [MonitoringController::class, 'storeDaily'])->name('store.daily-monitoring');
    Route::post('/monitoring/daily-update/{id}', [MonitoringController::class, 'updateDaily'])->name('update.daily-monitoring');
    Route::post('/monitoring/daily-mainlog/{id}', [MonitoringController::class, 'storeMainLog'])->name('store.daily-mainlog');
    Route::post('/monitoring/daily-mainlog-service/{id}', [MonitoringController::class, 'storeMainLogService'])->name('store.daily-mainlog-service');
    Route::delete('/monitoring/daily-mainlog/{id}', [MonitoringController::class, 'deleteMainLog'])->name('delete.daily-mainlog');
    Route::get('/monitoring/weekly/{id}', [MonitoringController::class, 'indexWeekly'])->name('index.weekly-monitoring');
    Route::get('/monitoring/weekly-create/{id}', [MonitoringController::class, 'createWeekly'])->name('create.weekly-monitoring');
    Route::get('/monitoring/weekly-edit/{id}', [MonitoringController::class, 'editWeekly'])->name('edit.weekly-monitoring');
    Route::post('/monitoring/weekly-store/{id}', [MonitoringController::class, 'storeWeekly'])->name('store.weekly-monitoring');
    Route::post('/monitoring/weekly-update/{id}', [MonitoringController::class, 'updateWeekly'])->name('update.weekly-monitoring');
    Route::get('/monitoring/monthly-create/{id}', [MonitoringController::class, 'createMonthly'])->name('create.monthly-monitoring');
    Route::post('/monitoring/monthly-store/{id}', [MonitoringController::class, 'storeMonthly'])->name('store.monthly-monitoring');
    Route::get('/monitoring-client/fajarPaper', [MonitoringClientController::class, 'index'])->name('monitoring.fajarPaper');
    Route::get('/monitoring-client/fajarPaper-detail/weekly', [MonitoringClientController::class, 'detailWeekly'])->name('monitoring.fajarPaper-detail-weekly');
    Route::get('/monitoring-client/fajarPaper-archive', [MonitoringClientController::class, 'indexArsip'])->name('monitoring-arsip.fajarPaper');
    Route::get('/monitoring-client/fajarPaper/{id}', [MonitoringClientController::class, 'show'])->name('monitoring.fajarPaper-detail');
    Route::get('/monitoring-client/fajarPaper-quotation/{id}', [MonitoringClientController::class, 'createQuotation'])->name('monitoring.create-quotation');
    Route::post('/monitoring-client/fajarPaper-quotation/{id}', [MonitoringClientController::class, 'storeQuotation'])->name('monitoring.store-quotation');
    Route::post('/monitoring-client/fajarPaper-addMainlog/{id}', [MonitoringClientController::class, 'addMainlog'])->name('monitoring.fajarPaper-addMainlog');
    Route::patch('/monitoring-client/fajarPaper-updateIssue/{id}', [MonitoringClientController::class, 'updateIssue'])->name('monitoring.fajarPaper-updateIssue');
    Route::patch('/monitoring-client/fajarPaper-updateRecommendation/{id}', [MonitoringClientController::class, 'updateRecommendation'])->name('monitoring.fajarPaper-updateRecommendation');
    Route::patch('/monitoring-client/fajarPaper-updateStatus/{id}', [MonitoringClientController::class, 'updateStatus'])->name('monitoring.fajarPaper-updateStatus');
    Route::patch('/monitoring-client/fajarPaper-arsipStatus/{id}', [MonitoringClientController::class, 'arsipStatus'])->name('monitoring.fajarPaper-arsipStatus');
    Route::patch('/monitoring-client/fajarPaper-backArsipStatus/{id}', [MonitoringClientController::class, 'backArsipStatus'])->name('monitoring.fajarPaper-backArsipStatus');
    Route::patch('/monitoring-client/fajarPaper-updatePN/{id}', [MonitoringClientController::class, 'updatePN'])->name('monitoring.fajarPaper-updatePN');
    Route::delete('/monitoring-client/fajarPaper-deletePN/{id}', [MonitoringClientController::class, 'deletePN'])->name('monitoring.fajarPaper-deletePN');
    Route::patch('/monitoring-client/fajarPaper/{id}', [MonitoringClientController::class, 'editIssue'])->name(name: 'monitoring.fajarPaper-editIssue');
    Route::get('/monitoring-client/fajarPaper-monitoring', [MonitoringClientController::class, 'monitoring'])->name('monitoring.fajarPaper-monitoring');
    Route::get('/monitoring-client/fajarPaper-service-report', [MonitoringClientController::class, 'service_report'])->name('monitoring.fajarPaper-service-report');
    Route::get('/monitoring-client/fajarPaper-reports', [MonitoringClientController::class, 'reports'])->name('monitoring.fajarPaper-reports');
    Route::get('/monitoring-client-fajarPaper-reports/{year}/{month}', [MonitoringClientController::class, 'reportsMonthly'])->name('monitoring.fajarPaper-reportsMonthly');
    Route::get('/monitoring-client/fajarPaper-summary-print', [MonitoringClientController::class, 'summaryPrint'])->name('monitoring.fajarPaper-summary-print');
    Route::get('/monitoring-client/fajarPaper-hold-print', [MonitoringClientController::class, 'holdPrint'])->name('monitoring.fajarPaper-hold-print');
    Route::get('/monitoring-client/fajarPaper-quote-print', [MonitoringClientController::class, 'quotePrint'])->name('monitoring.fajarPaper-quote-print');
    Route::get('/monitoring-client/fajarPaper-summary-print/{month}', [MonitoringClientController::class, 'summaryPrintMonth'])->name('monitoring.fajarPaper-summary-print-month');
    Route::get('/monitoring-client/fajarPaper-hold-print/{month}', [MonitoringClientController::class, 'holdPrintMonth'])->name('monitoring.fajarPaper-hold-print-month');
    Route::get('/monitoring-client/fajarPaper-quote-print/{month}', [MonitoringClientController::class, 'quotePrintMonth'])->name('monitoring.fajarPaper-quote-print-month');
    Route::post('/monitoring-client/accept-issue/{id}', [MonitoringClientController::class, 'acceptIssue'])->name('monitoring.accept-issue');
    Route::get('/monitoring-summary/{month}', [MonitoringController::class, 'summaryMainlog'])->name('summary.mainlog');
    Route::get('/monitoring-summary/print/{month}', [MonitoringController::class, 'summaryMainlogPrint'])->name('summary.mainlog-print');
    Route::get('/daily/activity', [MonitoringController::class, 'activity'])->name('mainActivity.index');
    Route::post('/check-planning', [MonitoringController::class, 'checkPlanning']);
    Route::post('/check-sync', [MonitoringController::class, 'checkSync']);
    Route::post('/check-abnormal', [MonitoringController::class, 'checkAbnormal']);
    Route::post('/check-log', [MonitoringController::class, 'checkLog']);
    Route::post('/check-timeline', [MonitoringController::class, 'checkTimeline']);
    Route::post('/check-preventive', [MonitoringController::class, 'checkPreventive']);

    Route::get('/db/monitoring/compressor/{id}/{month}', function ($id, $month) {
        $setday = Carbon::today();
        $today = $setday->setMonth($month);
        $year = $today->year;

        $startOfMonth = $today->copy()->startOfMonth();
        $startOfMonthDate = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $dates = [];
        for ($date = $startOfMonthDate; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('d-m-Y');
        }

        $monitoringData = Monitoring::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('id_machine', $id)
            ->join('users as u', 'u.id', '=', 'monitoring.id_pic')
            ->get(['monitoring.*', 'u.name'])
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('d-m-Y'); // Format tanggal
                return $item;
            });

        $compressorIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'running' => $item->running ?? '-',
                'loading' => $item->loading ?? '-',
                'pressure' => $item->pressure ?? '-',
                'temp' => $item->temp ?? '-',
                'leak' => $item->leak ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'issue' => $item->issue ?? '-',
                'pic' => $item->name ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $compressor = [];
        foreach ($dates as $date) {
            $compressor[] = [
                'date' => $date,
                'id' => $compressorIndexed[$date]['id'] ?? '-',
                'running' => $compressorIndexed[$date]['running'] ?? '-',
                'loading' => $compressorIndexed[$date]['loading'] ?? '-',
                'pressure' => $compressorIndexed[$date]['pressure'] ?? '-',
                'leak' => $compressorIndexed[$date]['leak'] ?? '-',
                'temp' => $compressorIndexed[$date]['temp'] ?? '-',
                'condition' => $compressorIndexed[$date]['condition'] ?? '-',
                'oil_level' => $compressorIndexed[$date]['oil_level'] ?? '-',
                'issue' => $compressorIndexed[$date]['issue'] ?? '-',
                'pic' => $compressorIndexed[$date]['pic'] ?? '-',
            ];
        }
        return response()->json(['data' => $compressor]);
    });
    Route::get('/db/monitoring/dryer/{id}/{month}', function ($id, $month) {
        $setday = Carbon::today();
        $today = $setday->setMonth($month);
        $year = $today->year;

        $startOfMonth = $today->copy()->startOfMonth();
        $startOfMonthDate = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $dates = [];
        for ($date = $startOfMonthDate; $date->lte($endOfMonth); $date->addDay()) {
            $dates[] = $date->format('d-m-Y');
        }

        $monitoringData = Monitoring::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('id_machine', $id)
            ->join('users as u', 'u.id', '=', 'monitoring.id_pic')
            ->get(['monitoring.*', 'u.name'])
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('d-m-Y'); // Format tanggal
                return $item;
            });

        $dryerIndexed = $monitoringData->keyBy('date')->map(function ($item) {
            return [
                'id' => $item->id ?? '-',
                'temp' => $item->temp ?? '-',
                'temp_out' => $item->temp_out ?? '-',
                'dew' => $item->dew ?? '-',
                'drain' => $item->drain ?? '-',
                'leak' => $item->leak ?? '-',
                'fan' => $item->fan ?? '-',
                'condition' => $item->condition ?? '-',
                'oil_level' => $item->oil_level ?? '-',
                'issue' => $item->issue ?? '-',
                'pic' => $item->name ?? '-',
            ];
        })->toArray();

        // Gabungkan data monitoring dengan daftar tanggal
        $dryer = [];
        foreach ($dates as $date) {
            $dryer[] = [
                'date' => $date,
                'id' => $dryerIndexed[$date]['id'] ?? '-',
                'temp' => $dryerIndexed[$date]['temp'] ?? '-',
                'temp_out' => $dryerIndexed[$date]['temp_out'] ?? '-',
                'dew' => $dryerIndexed[$date]['dew'] ?? '-',
                'drain' => $dryerIndexed[$date]['drain'] ?? '-',
                'condition' => $dryerIndexed[$date]['condition'] ?? '-',
                'oil_level' => $dryerIndexed[$date]['oil_level'] ?? '-',
                'issue' => $dryerIndexed[$date]['issue'] ?? '-',
                'leak' => $dryerIndexed[$date]['leak'] ?? '-',
                'fan' => $dryerIndexed[$date]['fan'] ?? '-',
                'pic' => $dryerIndexed[$date]['pic'] ?? '-',
            ];
        }
        return response()->json(['data' => $dryer]);
    });
    Route::get('/db/monitoring/issue/{id}/{month}', function ($id, $month) {
        $issue = Monitoring::leftJoin('pn_monitoring as pn', 'pn.id_monitoring', '=', 'monitoring.id')
            ->join('users as u', 'u.id', '=', 'monitoring.id_pic')
            ->where('id_machine', $id)
            ->whereNot('issue', '-')
            ->whereNot('issue', 'normal')
            ->whereNot('issue', 'Normal')
            ->whereNotNull('issue')
            ->whereMonth('monitoring.date', $month)
            ->whereYear('monitoring.date', Carbon::today()->year)
            ->select(
                DB::raw("DATE_FORMAT(monitoring.date, '%d-%m-%Y') as date"),
                'monitoring.id',
                'monitoring.issue',
                'monitoring.recommendation',
                'u.name',
                DB::raw("IFNULL(GROUP_CONCAT(pn.pn SEPARATOR ' | '), '-') as pn")
            )
            ->groupBy('monitoring.id')
            ->get();

        return response()->json(['data' => $issue]);
    });
    Route::get('/db/monitoring/mainlog/{id}/{month}', function ($id, $month) {
        $mainlog = Mainlog::join('users as u', 'u.id', '=', 'main_log.id_teknisi')
            ->where('id_machine', $id)
            ->whereMonth('date', $month)
            ->select('main_log.*', 'u.name')
            ->get();


        return response()->json(['data' => $mainlog]);
    });

    Route::get('/db/monitoring/semester', function () {
        $start = Carbon::create(2025, 1, 1); // Mulai dari Januari 2025
        $end = Carbon::create(2026, 12, 1); // Sampai Desember 2026

        $no_semester = 1;
        while ($start->lessThanOrEqualTo($end)) {
            $semester[] = [
                'id' => $no_semester,
                'semester' => 'Semester ' . $no_semester,
                'year' => $start->format('Y'),
                'semesterNum' => $no_semester,
            ];
            if ($start->month == '1') {
                $no_semester++;
            } else {
                $no_semester--;
            }
            $start->addMonths(6);
        }
        return response()->json(['data' => $semester]);
    });
    Route::get('/db/monitoring/summary', function () {
        $today = Carbon::today();
        $summary = Mainlog::join('machine as m', 'main_log.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->whereMonth('main_log.date', $today->month)
            ->whereYear('main_log.date', $today->year)
            ->select(
                'main_log.id',
                'main_log.desc',
                DB::raw("DATE_FORMAT(main_log.date, '%d-%m-%Y') as date"),
                'm.tag',
                'm.location',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )
            ->get();

        return response()->json(['data' => $summary]);
    });
    Route::get('/db/monitoring/quote', function () {
        $today = Carbon::today();
        $quoteMon = Monitoring::join('quotation as q', 'monitoring.id', '=', 'q.id_monitoring')
            ->join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->whereMonth('monitoring.date', $today)
            ->whereYear('monitoring.date', $today)
            ->select(
                'monitoring.*',
                'm.tag',
                'm.location',
                'q.title',
                'q.no_quote',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )
            ->get();
        return response()->json(['data' => $quoteMon]);
    });
    Route::get('/db/monitoring/hold', function () {
        $today = Carbon::today();
        $latestStatus = StatusMonitoring::selectRaw('MAX(id) as id')
            ->groupBy('id_monitoring');

        $statusMon = StatusMonitoring::join('monitoring', 'monitoring.id', '=', 'status_monitoring.id_monitoring')
            ->join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->whereIn('status_monitoring.id', $latestStatus)
            ->whereIn('status_monitoring.status', ['3', '0', '1'])
            ->whereMonth('monitoring.date', $today)
            ->whereYear('monitoring.date', $today)
            ->select(
                'monitoring.*',
                'm.tag',
                'status_monitoring.desc as status_desc',
                'status_monitoring.status',
                'm.location',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )
            ->get();
        return response()->json(['data' => $statusMon]);
    });

    Route::get('db/monitoring/reports', function () {
        $data = Monitoring::join(DB::raw("(SELECT sm1.* FROM status_monitoring sm1 
                    WHERE sm1.id = (SELECT MAX(sm2.id) FROM status_monitoring sm2 WHERE sm2.id_monitoring = sm1.id_monitoring)
                ) as sm"), 'monitoring.id', '=', 'sm.id_monitoring')
            ->join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->join('users as u', 'monitoring.id_pic', '=', 'u.id')
            ->whereIn('sm.status', ['1', '2', '3'])
            ->where('m.id_client', 1277)
            ->where('monitoring.issue', '!=', '-')
            ->whereNotNull('monitoring.issue')
            ->groupBy('monitoring.id')
            ->select(
                'monitoring.*',
                'm.desc as name',
                'm.tag',
                'm.location',
                'sm.status',
                'sm.desc as status_desc',
                'monitoring.id as monId',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )->get();

        return response()->json(['data' => $data]);
    });
    Route::get('/db/monitoring/summary/{year}/{month}', function ($year, $month) {
        $today = Carbon::today();
        $summary = Monitoring::join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('main_log as ml', 'ml.id_issue', '=', 'monitoring.id')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->whereMonth('monitoring.date', $month)
            ->whereYear('monitoring.date', $year)
            ->select(
                'monitoring.*',
                'ml.desc',
                'm.tag',
                'm.location',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )
            ->get();
        return response()->json(['data' => $summary]);
    });
    Route::get('/db/monitoring/quote/{year}/{month}', function ($year, $month) {
        $today = Carbon::today();
        $quoteMon = Monitoring::join('quotation as q', 'monitoring.id', '=', 'q.id_monitoring')
            ->join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->whereMonth('monitoring.date', $month)
            ->whereYear('monitoring.date', $year)
            ->select(
                'monitoring.*',
                'm.tag',
                'm.location',
                'q.title',
                'q.no_quote',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )
            ->get();
        return response()->json(['data' => $quoteMon]);
    });
    Route::get('/db/monitoring/hold/{year}/{month}', function ($year, $month) {
        $today = Carbon::today();
        $latestStatus = StatusMonitoring::selectRaw('MAX(id) as id')
            ->groupBy('id_monitoring');

        $statusMon = StatusMonitoring::join('monitoring', 'monitoring.id', '=', 'status_monitoring.id_monitoring')
            ->join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->whereIn('status_monitoring.id', $latestStatus)
            ->whereIn('status_monitoring.status', ['3', '0', '1'])
            ->whereMonth('monitoring.date', $month)
            ->whereYear('monitoring.date', $year)
            ->select(
                'monitoring.*',
                'm.tag',
                'status_monitoring.desc as status_desc',
                'status_monitoring.status',
                'm.location',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )
            ->get();
        return response()->json(['data' => $statusMon]);
    });

    //service manager
    Route::get('/service-manager-prokemas', [MonitoringController::class, 'indexServiceProkemas'])->name('service-manager-prokemas.index');
    Route::get('/service-manager-daily-prokemas/{id}/{month}', [MonitoringController::class, 'visitorDailyServiceProkemas'])->name('service-manager-daily-prokemas.visit');
    Route::get('/service-manager', [MonitoringController::class, 'indexServiceM'])->name('service-manager.index');
    Route::get('/service-manager/{id}', [MonitoringController::class, 'showServiceM'])->name('service-manager.show');
    Route::get('/service-manager-daily/{id}/{month}', [MonitoringController::class, 'visitorDailyService'])->name('service-manager-daily.visit');
    Route::patch('/service-manager-daily-issue/{id}/{month}', [MonitoringController::class, 'issueUpdate'])->name('service-manager-daily.issue-update');
    Route::patch('/service-manager-daily-mainlog/{id}/{month}', [MonitoringController::class, 'mainlogUpdate'])->name('service-manager-daily.mainlog-update');
    Route::get('/service-manager-weekly/{id}/{week}', [MonitoringController::class, 'visitorWeeklyService'])->name('service-manager-weekly.visit');
    Route::get('/service-manager-monthly/{id}/{month}', [MonitoringController::class, 'visitorMonthlyService'])->name('service-manager-monthly.visit');
    Route::get('/service-manager-daily-print/{id}/{month}', [MonitoringController::class, 'visitorDailyServicePrint'])->name('service-manager-daily.print');
    Route::get('/service-manager-daily-print-prokemas/{id}/{month}', [MonitoringController::class, 'visitorDailyServicePrintProkemas'])->name('service-manager-daily-prokemas.print');
    Route::get('/service-manager-weekly-print/{id}/{week}', [MonitoringController::class, 'visitorWeeklyServicePrint'])->name('service-manager-weekly.print');
    Route::get('/service-manager-monthly-print/{id}/{week}', [MonitoringController::class, 'visitorMonthlyServicePrint'])->name('service-manager-monthly.print');
    Route::get('/service-manager-recap-day/{month}/{year}', [MonitoringController::class, 'recapDay'])->name('service-manager.recap');
    Route::get('/service-manager-recap-week/{month}/{year}', [MonitoringController::class, 'recapWeek'])->name('service-manager.recap-week');
    Route::get('/service-manager/allRec/{date?}', [MonitoringController::class, 'getAllMachine'])->name('service-manager.allrecap-monitoring');
    Route::get('/service-manager/issue/{date}', [MonitoringController::class, 'issueMachine'])->name('service-manager.issue');
    Route::get('/db/service-manager/bulan/{id}', function ($id) {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('F');
            $months[] = [
                'id' => $id,
                'month' => strtoupper($monthName),
                'monthNum' => $i,
            ];
        }
        return response()->json(['data' => $months]);
    });
    Route::get('/db/service-manager/weekly/{id}', function ($id) {
        $year = Carbon::now()->year;

        // Tanggal awal dan akhir tahun
        $startOfYear = Carbon::createFromDate($year, 1, 1);
        $endOfYear = Carbon::createFromDate($year, 12, 31);

        $weeks = [];
        $weekNumber = 1;

        // Minggu pertama (dari tanggal 1 hingga hari Minggu pertama)
        $firstDate = $startOfYear->copy();
        $lastDate = $startOfYear->copy()->endOfWeek(Carbon::SUNDAY);
        $weeks[] = [
            'week' => $weekNumber,
            'firstdate' => $firstDate->format('j-n-Y'),
            'lastdate' => $lastDate->format('j-n-Y'),
            'id' => $id,
        ];
        $weekNumber++;

        // Minggu-minggu berikutnya (dari Senin hingga Minggu)
        $currentStartDate = $lastDate->addDay()->startOfWeek(Carbon::MONDAY);

        while ($currentStartDate->lte($endOfYear)) {
            $firstDate = $currentStartDate->copy();
            $lastDate = $currentStartDate->copy()->endOfWeek(Carbon::SUNDAY);

            // Hindari melewati akhir tahun
            if ($lastDate->gt($endOfYear)) {
                $lastDate = $endOfYear;
            }

            $weeks[] = [
                'week' => $weekNumber,
                'firstdate' => $firstDate->format('j-n-Y'),
                'lastdate' => $lastDate->format('j-n-Y'),
                'id' => $id,
            ];

            $weekNumber++;
            $currentStartDate->addWeek();
        }

        return response()->json(['data' => $weeks]);
    });
    Route::get('/db/service-manager/monthly/{id}', function ($id) {
        $year = Carbon::now()->year;

        // Tanggal awal dan akhir tahun
        $startOfYear = Carbon::createFromDate($year, 1, 1);
        $endOfYear = Carbon::createFromDate($year, 12, 31);

        $months = [];
        $monthNumber = 1;

        // Minggu pertama (dari tanggal 1 hingga hari Minggu pertama)
        $firstDate = $startOfYear->copy();
        $lastDate = $startOfYear->copy()->endOfmonth();
        $months[] = [
            'monthNum' => $monthNumber,
            'month' => $firstDate->format('F'),
            'firstdate' => $firstDate->format('j-n-Y'),
            'lastdate' => $lastDate->format('j-n-Y'),
            'id' => $id,
        ];
        $monthNumber++;

        // Minggu-minggu berikutnya (dari Senin hingga Minggu)
        $currentStartDate = $lastDate->addDay()->startOfMonth();

        while ($currentStartDate->lte($endOfYear)) {
            $firstDate = $currentStartDate->copy();
            $lastDate = $currentStartDate->copy()->endOfMonth();

            // Hindari melewati akhir tahun
            if ($lastDate->gt($endOfYear)) {
                $lastDate = $endOfYear;
            }

            $months[] = [
                'monthNum' => $monthNumber,
                'month' => $firstDate->format('F'),
                'firstdate' => $firstDate->format('j-n-Y'),
                'lastdate' => $lastDate->format('j-n-Y'),
                'id' => $id,
            ];

            $monthNumber++;
            $currentStartDate->addMonth();
        }

        return response()->json(['data' => $months]);
    });
    Route::get('db/monitoring/issue', function () {
        $data = Monitoring::join('status_monitoring as sm', 'monitoring.id', '=', 'sm.id_monitoring')
            ->join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->join('users as u', 'monitoring.id_pic', '=', 'u.id')
            ->where('m.id_client', 1277)
            ->where('sm.status', '0')
            ->where('monitoring.issue', '!=', '-')
            ->whereNotNull('monitoring.issue')
            ->groupBy('monitoring.id')
            ->select(
                'monitoring.*',
                'u.name',
                'm.tag',
                'm.location',
                'monitoring.id as monId',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )->get();
        return response()->json(['data' => $data]);
    });
    Route::get('db/monitoring/arsip', function () {
        $data = Monitoring::join(DB::raw("(SELECT sm1.* FROM status_monitoring sm1 
                    WHERE sm1.id = (SELECT MAX(sm2.id) FROM status_monitoring sm2 WHERE sm2.id_monitoring = sm1.id_monitoring)
                ) as sm"), 'monitoring.id', '=', 'sm.id_monitoring')
            ->join('machine as m', 'monitoring.id_machine', '=', 'm.id')
            ->join('serial_product as sp', 'sp.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->join('users as u', 'monitoring.id_pic', '=', 'u.id')
            ->where('sm.status', '5')
            ->where('m.id_client', 1277)
            ->where('monitoring.issue', '!=', '-')
            ->whereNotNull('monitoring.issue')
            ->groupBy('monitoring.id')
            ->select(
                'monitoring.*',
                'm.desc as name',
                'm.tag',
                'm.location',
                'sm.status',
                'sm.desc as status_desc',
                'monitoring.id as monId',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )->get();

        return response()->json(['data' => $data]);
    });
    Route::get('db/recap-compressor/{date}', function ($date) {
        $mesinCompressor = Machine::leftJoin('monitoring as m', function ($join) use ($date) {
            $join->on('machine.id', '=', 'm.id_machine')
                ->whereDate('m.date', '=', strval($date)); // Menyaring berdasarkan tanggal monitoring
        })
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic')
            ->where('machine.id_client', 1277)
            ->whereNotBetween('machine.id', [472, 481])
            ->groupBy('machine.id')
            ->select(
                'machine.*',
                DB::raw("CONCAT(sp.brand, ' ', u.sku) as brand_type"),
                'm.*',
                'u.unit',
                'us.name'
            )
            ->get();
        return response()->json(['data' => $mesinCompressor]);
    });
    Route::get('db/recap-dryer/{date}', function ($date) {
        $mesinDryer = Machine::leftJoin('monitoring as m', 'machine.id', '=', 'm.id_machine')
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic')
            ->where('machine.id_client', 1277)
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->whereDate('m.date', $date)
            ->orderBy('machine.location')
            ->select(
                DB::raw("CONCAT(sp.brand, ' ', u.sku) as brand_type"),
                'm.*',
                'machine.*',
                'us.name'
            )
            ->get();
        return response()->json(['data' => $mesinDryer]);
    });
    Route::get('db/recap-dryer-week/{week}/{date}', function ($week, $date) {
        $date = Carbon::parse($date);

        $month = $date->month;
        $year = $date->year;
        $mesinDryer = Machine::leftJoin('monitoring_weekly as m', function ($join) use ($week, $month, $year) {
            $join->on('machine.id', '=', 'm.id_machine')
                ->where('m.week', $week)
                ->where(function ($q) use ($month, $year) {
                    $q->whereNull('m.date') // kalau null, mesin tetap muncul
                        ->orWhere(function ($q2) use ($month, $year) {
                            $q2->whereMonth('m.date', $month)
                                ->whereYear('m.date', $year);
                        });
                });
        })
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic')
            ->where('machine.id_client', 1277)
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->orderBy('machine.location')
            ->select(
                'machine.*',
                DB::raw("CONCAT(sp.brand, ' ', u.sku) as brand_type"),
                'm.*',
                'us.name'
            )
            ->get();
        return response()->json(['data' => $mesinDryer]);
    });
    Route::get('db/recap-dryer-today', function () {
        $date = Carbon::today();
        $mesinDryer = Machine::leftJoin('monitoring as m', function ($join) use ($date) {
            $join->on('machine.id', '=', 'm.id_machine')
                ->whereDate('m.date', '=', strval($date)); // Menyaring berdasarkan tanggal monitoring
        })
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic')
            ->where('machine.id_client', 1277)
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->orderBy('machine.location')
            ->select(
                'machine.*',
                DB::raw("CONCAT(sp.brand, ' ', u.sku) as brand_type"),
                'm.*',
                'us.name'
            )
            ->get();
        return response()->json(['data' => $mesinDryer]);
    });
    Route::get('/db/bulan', function () {
        $months = [];
        $start = Carbon::create(2025, 1, 1); // Mulai dari Januari 2025
        $end = Carbon::create(2026, 12, 1); // Sampai Desember 2026

        while ($start->lessThanOrEqualTo($end)) {
            $months[] = [
                'id' => $start->month,
                'month' => $start->translatedFormat('F Y'),
                'date' => $start->format('d-m-Y'),
                'monthNum' => $start->month,
                'year' => $start->year,
            ];
            $start->addMonth();
        }

        return response()->json(['data' => $months]);
    });
    Route::get('/db/days/{month}/{year}', function ($month, $year) {
        if (!checkdate($month, 1, $year)) {
            return response()->json(['error' => 'Invalid month or year'], 400);
        }

        $days = [];
        $date = Carbon::create($year, $month, 1);

        $start = $date->copy()->startOfMonth(); // Salin tanggal awal bulan
        $end = $date->copy()->endOfMonth();    // Salin tanggal akhir bulan

        $currentDate = $start->copy(); // Pastikan `currentDate` tidak mengubah `$start`

        while ($currentDate->lessThanOrEqualTo($end)) {
            $days[] = [
                'id' => $currentDate->day,
                'days' => $currentDate->toFormattedDateString(),
                'day_name' => $currentDate->format('l'),
                'date' => $currentDate->format('Y-m-d'), // Gunakan `$currentDate`
                'year' => $currentDate->year,
            ];
            $currentDate->addDay(); // Tambah 1 hari ke `$currentDate`
        }

        return response()->json(['data' => $days]);
    });
    Route::get('/db/weeks/{month}/{year}', function ($month, $year) {
        if (!checkdate($month, 1, $year)) {
            return response()->json(['error' => 'Invalid month or year'], 400);
        }
        $weeks = [];
        $date = Carbon::create($year, $month, 1);
        for ($week = 1; $week <= 5; $week++) {
            $weeks[] = [
                'id' => $week,
                'weeks' => $week,
                'week_name' => 'Week ' . $week,
                'date' => $date->format('Y-m-d'),
                'year' => $date->year,
            ];
        }
        return response()->json(['data' => $weeks]);
    });
    Route::get('/db/service-reports/fp', function () {
        $today = Carbon::today();
        $data = Reports::join('machine as m', 'm.id', '=', 'reports.id_machine')
            ->join('users as u', 'u.id', '=', 'reports.id_technician')
            ->join('client as c', 'c.id', '=', 'm.id_client')
            ->join('serial_product as s', 's.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 's.id_product')
            ->where('c.id', 1277)
            ->whereBetween('reports.date', ['2025-01-01', $today])
            ->select(
                'reports.id',
                'reports.no_service',
                'reports.jobdesc',
                'reports.date',
                'u.name',
                'm.tag',
                'm.location',
                DB::raw("CONCAT(s.brand, ' ', un.sku) as brand_type")
            )
            ->get();

        return response()->json(['data' => $data]);
    });

    Route::get('/db/service-reports/sales', function () {
        $today = Carbon::today();
        $year = $today->year;
        $data = Reports::join('machine as m', 'm.id', '=', 'reports.id_machine')
            ->join('client as c', 'c.id', '=', 'm.id_client')
            ->join('users as u', 'u.id', '=', 'c.id_sales')
            ->join('users as t', 't.id', '=', 'reports.id_technician')
            ->join('serial_product as s', 's.id', '=', 'm.id_unit')
            ->join('unit as un', 'un.id', '=', 's.id_product')
            ->where('u.id', Auth::user()->id)
            // ->whereYear('reports.date', $year)
            ->select(
                'reports.id',
                'reports.no_service',
                'reports.jobdesc',
                'reports.date',
                'reports.viewed',
                'c.company',
                't.name',
                'm.tag',
                'm.location',
                DB::raw("CONCAT(s.brand, ' ', un.sku) as brand_type"),
                DB::raw("CONCAT('(', COALESCE(m.serial, '-'), ') - ', COALESCE(m.tag, '-')) AS serial_tag")
            )
            ->get();

        return response()->json(['data' => $data]);
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
    Route::get('/index/invoice/kojisha', [InvoiceController::class, 'index_kojisha'])->name('invoice.index_kojisha');
    Route::get('/request/invoice/{id}', [InvoiceController::class, 'before_accept'])->name('before.accept');
    Route::get('/request/invoice', [InvoiceController::class, 'request'])->name('invoice.request');
    Route::get('/invoice/print/{id}', [InvoiceController::class, 'print_invoice'])->name('print.invoice');
    Route::post('/invoice/sign/{id}', [InvoiceController::class, 'hand_sign'])->name('invoice.sign');
    Route::post('/invoice/date/{id}', [InvoiceController::class, 'change_date'])->name('invoice.date');
    Route::post('/invoice/date-label/{id}', [DeliveryController::class, 'change_date_label'])->name('invoice.date_label');
    Route::get('/invoice/do_ekspedisi/{id}', [InvoiceController::class, 'do_ekspedisi'])->name('invoice.do_ekspedisi');
    Route::get('/invoice/print_ekspedisi/{id}', [InvoiceController::class, 'print_ekspedisi'])->name('invoice.print_ekspedisi');
    Route::post('/invoice/form_ekspedisi/{id}', [InvoiceController::class, 'form_ekspedisi'])->name('invoice.form_ekspedisi');
    Route::get('/invoice/do_teknisi/{id}', [InvoiceController::class, 'do_teknisi'])->name('invoice.do_teknisi');
    Route::get('/invoice/print_teknisi/{id}', [InvoiceController::class, 'print_teknisi'])->name('invoice.print_teknisi');
    Route::post('/invoice/form_teknisi/{id}', [InvoiceController::class, 'form_teknisi'])->name('invoice.form_teknisi');
    Route::delete('/invoice/del-sign/{id}', [InvoiceController::class, 'delete_hand_sign'])->name('invoice.del-sign');
    Route::delete('/invoice/del-expense/{id}', [InvoiceController::class, 'deleteExpense'])->name('invoice.del-expense');
    Route::post('/invoice/pph/{id}', [InvoiceController::class, 'add_pph'])->name('invoice.pph');
    Route::post('/invoice/pph_manual/{id}', [InvoiceController::class, 'add_pph_manual'])->name('invoice.pph_manual');
    Route::patch('/invoice/delete_pph_manual/{id}', [InvoiceController::class, 'delete_pph_manual'])->name('invoice.delete_pph_manual');
    Route::post('/invoice/pph_service/{id}', [InvoiceController::class, 'add_pph_service'])->name('invoice.pph_service');
    Route::post('/invoice/expense/{id}', [InvoiceController::class, 'inputExpense'])->name('invoice.expense');
    Route::delete('/invoice/del-pph/{id}', [InvoiceController::class, 'delete_pph'])->name('invoice.del-pph');
    Route::delete('/invoice/del-pph-service/{id}', [InvoiceController::class, 'delete_pph_service'])->name('invoice.del-pph-service');
    Route::get('/invoice/label_detail/{id}', [InvoiceController::class, 'label_detail'])->name('invoice.label_detail');
    Route::get('/invoice/label_print/{id}', [InvoiceController::class, 'label_print'])->name('invoice.label_print');
    Route::post('/invoice/change_desc/{id}', [InvoiceController::class, 'change_desc'])->name('invoice.change_desc');
    Route::post('/invoice/confirm_payment/{id}', [InvoiceController::class, 'confirm_payment'])->name('invoice.confirm_payment');
    Route::post('/invoice/due_date/{id}', [InvoiceController::class, 'due_date'])->name('invoice.due_date');
    Route::post('/invoice/undo_confirm_payment/{id}', [InvoiceController::class, 'undo_confirm_payment'])->name('invoice.undo_confirm_payment');

    // Payment
    Route::get('/payment-index/invoice', [PaymentController::class, 'index_invoice'])->name('payment_index.invoice');
    Route::get('/payment-index/invoice-ahmad', [PaymentController::class, 'index_invoice_ahmad'])->name('payment_index.invoice_ahmad');
    Route::get('/payment-index/invoice-rayi', [PaymentController::class, 'index_invoice_rayi'])->name('payment_index.invoice_rayi');
    Route::get('/payment-detail/invoice/{id}', [PaymentController::class, 'detail_invoice'])->name('payment_detail.invoice');
    Route::get('/payment-index/payment', [PaymentController::class, 'index_payment'])->name('payment_index.payment');
    Route::get('/payment-detail/payment/{id}', [PaymentController::class, 'detail_payment'])->name('payment_detail.payment');
    Route::get('/payment-index/aging', [PaymentController::class, 'index_aging'])->name('payment_index.aging');
    Route::get('/payment-detail/aging/{id}', [PaymentController::class, 'detail_aging'])->name('payment_detail.aging');
    Route::post('/confirm-payment/payment/{id}', [PaymentController::class, 'confirm_payment'])->name('confirm_payment.payment');
    Route::post('/unconfirm-payment/payment/{id}', [PaymentController::class, 'unconfirm_payment'])->name('unconfirm_payment.payment');
    Route::post('/reminder-payment/payment/{id}', [PaymentController::class, 'reminder_payment'])->name('reminder_payment.payment');
    Route::post('/reminder-calendar/payment/', [PaymentController::class, 'reminder_calendar'])->name('reminder_calendar.payment');
    Route::get('/view-payment/payment/{id}', [PaymentController::class, 'view_payment'])->name('view_payment.payment');
    Route::post('/payment/addPph/{id}', [PaymentController::class, 'addPph'])->name('payment.addPph');
    Route::post('/payment/editDate/{id}', [PaymentController::class, 'editDate'])->name('payment.editDate');

    Route::resource('/delivery', DeliveryController::class);
    Route::get('/delivery/print/{id}', [DeliveryController::class, 'print_delivery'])->name('print.delivery');
    Route::post('/delivery/change_date/{id}', [DeliveryController::class, 'change_date'])->name('change_date.delivery');
    Route::post('/delivery/change_desc/{id}', [DeliveryController::class, 'change_desc'])->name('delivery.change_desc');
    Route::post('/delivery/store_manual/{id}', [DeliveryController::class, 'store_manual'])->name('delivery.store_manual');
    Route::get('/delivery/manual/{id}', [DeliveryController::class, 'show_manual'])->name('delivery.show_manual');
    Route::get('/delivery/manual-print/{id}', [DeliveryController::class, 'print_delivery_manual'])->name('delivery.print_manual');
    Route::get('/delivery/create-teknisi-manual/{id}', [DeliveryController::class, 'create_manual_teknisi'])->name('delivery.create_manual_teknisi');
    Route::get('/delivery/create-ekspedisi-manual/{id}', [DeliveryController::class, 'create_manual_ekspedisi'])->name('delivery.create_manual_ekspedisi');

    Route::resource('/template', TemplateController::class);
    Route::get('/template/machine_template/{id}', [TemplateController::class, 'create_template'])->name('template.create_template');
    Route::post('/template/store_template/{id}', [TemplateController::class, 'store_template'])->name('template.store_template');
    Route::get('/db/template-machine', function () {
        $machine = MachineTemplate::select(
            'id',
            'kw',
            'hp',
            'brand',
            'sku',
            DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y') AS created"),
            DB::raw("CONCAT(kw, ' / ', hp) AS kw_hp")
        )->get();
        return response()->json(['data' => $machine]);
    });

    Route::resource('/return', ReturnController::class);
    Route::post('/accept/return/{id}', [ReturnController::class, 'accept_return'])->name('return.accept');
    Route::get('/db/request-return', function () {
        $return = ReturnQ::join('quotation as q', 'q.id', '=', 'return.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('return.lvl', '0')
            ->get([
                'return.*',
                'u.name',
                'c.company',
                'q.no_quote'
            ]);
        return response()->json(['data' => $return]);
    });
    Route::get('/db/return', function () {
        $return = ReturnQ::join('quotation as q', 'q.id', '=', 'return.id_quotation')
            ->join('pic as p', 'p.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'p.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->where('return.lvl', '1')
            ->get([
                'return.*',
                'u.name',
                'c.company',
                'q.no_quote'
            ]);
        return response()->json(['data' => $return]);
    });

    Route::resource('/warehouse', WarehouseController::class);

    Route::resource('/req-visit', ReqVisitController::class);
    Route::post('/req-visit/visited/{id}', [ReqVisitController::class, 'reportsWithRequest'])->name('req-visit.visited');
    Route::post('/req-visit/reports/{id}', [ReqVisitController::class, 'visited'])->name('req-visit.reports');
    Route::get('/db/req-visit/{id}', function ($id) {
        $userId = Auth::user()->id;
        $visits = DB::table('req_visit as r')
            ->join('machine as m', 'r.id_machine', '=', 'm.id')
            ->join('unit as un', 'un.id', '=', 'm.id_unit')
            ->join('serial_product as s', 'un.id', '=', 's.id_product')
            ->join('client as c', 'm.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->select('r.*', 'c.company', 'u.name', DB::raw("CONCAT(s.brand, ' ', un.sku) AS machine"))
            ->where('u.id', $userId)
            ->where('c.id', $id)
            ->groupBY('r.id', 'un.id')
            ->orderBy('r.req_date', 'ASC')
            ->get();

        return response()->json(['data' => $visits]);
    });
    Route::get('/db/req-visit', function () {
        $userId = Auth::user()->id;
        $visits = DB::table('req_visit as r')
            ->join('machine as m', 'r.id_machine', '=', 'm.id')
            ->join('client as c', 'm.id_client', '=', 'c.id')
            ->join('users as u', 'c.id_sales', '=', 'u.id')
            ->select('r.*', 'c.company', 'u.name', DB::raw("CONCAT(m.brand, ' ', m.type) AS machine"))
            ->where('u.id', $userId)
            ->orderBy('r.req_date', 'ASC')
            ->get();

        return response()->json(['data' => $visits]);
    });
    Route::get('/db/reqVisit/accept', function () {
        require_once base_path('app/api/reqVisit/connectionAccept.php');
    });
    Route::get('/db/reqVisit/coordinator', function () {
        require_once base_path('app/api/reqVisit/connectionSerCo.php');
    });


    Route::get('/archive/quotation', [ArchiveController::class, 'archive_quotation'])->name('archive.quotation');
    Route::post('/un-archive/quotation/{id}', [ArchiveController::class, 'unarchive_quotation'])->name('unarchive.quotation');
    Route::delete('/delete-archive/quotation/{id}', [ArchiveController::class, 'delete_archive_quotation'])->name('delete-archive.quotation');

    Route::resource('/prospect', ProspectController::class);
    Route::post('/prospect/add_sales/{id}', [ProspectController::class, 'add_sales'])->name('add_sales.prospect');
    Route::post('/prospect/without_quotation/{id}', [ProspectController::class, 'without_quotation'])->name('without_quotation.prospect');
    Route::post('/prospect/with_quotation/{id}', [ProspectController::class, 'with_quotation'])->name('with_quotation.prospect');
    Route::post('/prospect/onProcessFU/{id}', [ProspectController::class, 'onProcessFU'])->name('onProcessFU.prospect');
    Route::post('/prospect/no_respond/{id}', [ProspectController::class, 'no_respond'])->name('no_respond.prospect');
    Route::post('/prospect/no_provide/{id}', [ProspectController::class, 'no_provide'])->name('no_provide.prospect');
    Route::get('/prospect/create_quotation/{id}', [ProspectController::class, 'create_quotation'])->name('create_quotation.prospect');
    Route::post('/prospect/store_quotation/{id}', [ProspectController::class, 'store_quotation'])->name('store_quotation.prospect');
    Route::post('/prospect/choose_quotation/{id}', [ProspectController::class, 'choose_quotation'])->name('choose_quotation.prospect');
    Route::post('/prospect/add_comment/{id}', [ProspectController::class, 'add_comment'])->name('add_comment.prospect');
    Route::post('/prospect/{id}/view_comment', [ProspectController::class, 'view_comment'])->name('view_comment.prospect');

    Route::resource('/library', LibraryController::class);
    Route::get('/library/index/marktool', [LibraryController::class, 'index_marktool'])->name(name: 'marktool.index');
    Route::get('/library/index/brosur', [LibraryController::class, 'index_brosur'])->name(name: 'brosur.index');
    Route::get('/library/index/partlist', [LibraryController::class, 'index_partlist'])->name(name: 'partlist.index');
    Route::get('/library/index/manbook', [LibraryController::class, 'index_manbook'])->name(name: 'manbook.index');
    Route::post('/library/store/marktool', [LibraryController::class, 'store_marktool'])->name(name: 'store_marktool.library');
    Route::post('/library/store/brosur', [LibraryController::class, 'store_brosur'])->name(name: 'store_brosur.library');
    Route::post('/library/store/partlist', [LibraryController::class, 'store_partlist'])->name(name: 'store_partlist.library');
    Route::post('/library/store/manbook', [LibraryController::class, 'store_manbook'])->name(name: 'store_manbook.library');

    Route::resource('/notulen', NotulenController::class);

    // Pending PO
    Route::resource('/pending-po', PendingController::class);
    Route::patch('/pending-po/connect/{id}', [PendingController::class, 'connect_out'])->name('pending-po.connect_out');
    Route::patch('/pending-po/product/{id}', [PendingController::class, 'productEdit'])->name('pending-po.productEdit');
    Route::patch('/pending-po/project/{id}', [PendingController::class, 'projectEdit'])->name('pending-po.projectEdit');
    Route::patch('/pending-po/status/{id}', [PendingController::class, 'statusEdit'])->name('pending-po.statusEdit');
    Route::patch('/pending-po/delivery/{id}', [PendingController::class, 'deliveryEdit'])->name('pending-po.deliveryEdit');
    Route::post('/pending-po/retur/{id}', [PendingController::class, 'returProduct'])->name('pending-po.returProduct');
    Route::post('/pending-po/clear-return/{id}', [PendingController::class, 'clearReturn'])->name('pending-po.clearReturn');
    Route::post('/pending-po/resi/{id}', [PendingController::class, 'upload_resi'])->name('pending-po.resiEdit');
    Route::delete('/pending-po/delete-resi/{id}', [PendingController::class, 'delete_resi'])->name('pending-po.resiDelete');
    Route::post('/pending-po/comment/{id}', [PendingController::class, 'add_comment'])->name('pending-po.addComment');
    Route::get('/pending-po/product-out/{id}', [PendingController::class, 'pending_out'])->name('pending-po.product_out');
    Route::get('/pending-po/product-out-project/{id}', [PendingController::class, 'pending_out_project'])->name('pending-po.product_out_project');
    Route::post('/pending-po/product-out/{id}', [PendingController::class, 'product_out'])->name('pending-po.product_out');
    Route::post('/pending-po/done/{id}', [PendingController::class, 'donePending'])->name('pending-po.donePending');
    Route::get('/pending-po-done', [PendingController::class, 'indexDone'])->name('pending-po.done');
    Route::get('/pending-po-project', [PendingController::class, 'indexProject'])->name('pending-po.index-project');

    Route::get('/new-order', [PendingController::class, 'indexOrder'])->name('pending-po.order');
    Route::get('/sales-order', [PendingController::class, 'indexSOrder'])->name('pending-po.sales-order');
    Route::post('/sales-order-schedule/{id}', [PendingController::class, 'schedule'])->name('sales-order.schedule');
    Route::post('/sales-order-reschedule/{id}', [PendingController::class, 'reschedule'])->name('sales-order.reschedule');
    Route::post('/sales-order-dokumentasi/{id}', [PendingController::class, 'dokumentasi'])->name('sales-order.dokumentasi');
    Route::get('/sales-order/list', [PendingController::class, 'indexList'])->name('pending-po.list');
    Route::get('/sales-order/delivery', [PendingController::class, 'indexDelivery'])->name('pending-po.delivery');
    Route::get('/sales-order/completed', [PendingController::class, 'indexCompleted'])->name('pending-po.completed');

    // Change Warehouse
    Route::resource('/change-warehouse', ChangeWarehouseController::class);
    Route::post('/change-warehouse/accept/{id}', [ChangeWarehouseController::class, 'accept'])->name('change-warehouse.accept');

    // account
    Route::get('/expense-account', [ExpenseController::class, 'indexAccount'])->name('expense-account.index');
    Route::post('/expense-account', [ExpenseController::class, 'storeAccount'])->name('expense-account.store');
    Route::patch('/expense-account/{id}', [ExpenseController::class, 'updateAccount'])->name('expense-account.update');
    Route::delete('/expense-account/{id}', [ExpenseController::class, 'deleteAccount'])->name('expense-account.delete');
    Route::get('/expense', [ExpenseController::class, 'indexexpense'])->name('expense.index');
    Route::get('/expense-umum', [ExpenseController::class, 'indexexpenseUmum'])->name('expense-umum.index');
    Route::get('/expense/create', [ExpenseController::class, 'createexpense'])->name('expense.create');
    Route::get('/expense-umum/create', [ExpenseController::class, 'createexpenseUmum'])->name('expense-umum.create');
    Route::delete('/expense/{id}', [ExpenseController::class, 'deleteexpense'])->name('expense.delete');
    Route::get('/expense/{id}', [ExpenseController::class, 'showexpense'])->name('expense.show');
    Route::get('/expense-print/{id}', [ExpenseController::class, 'showexpensePrint'])->name('expense.print');
    Route::get('/get/account/{id}', [ExpenseController::class, 'getAccount'])->name('expense.getAccount');
    Route::post('/expense/store', [ExpenseController::class, 'storeexpense'])->name('expense.store');

    Route::get('/income', [ExpenseController::class, 'indexIncome'])->name('expense-income.index');
    Route::post('/income', [ExpenseController::class, 'storeIncome'])->name('expense-income.store');
    Route::get('/income-print/{mounth}/{year}', [ExpenseController::class, 'printBulan'])->name('expense-income.print-bulan');
    Route::get('/income-print/{year}', [ExpenseController::class, 'printTahun'])->name('expense-income.print-tahun');

    Route::get('/balance', [ExpenseController::class, 'indexBalance'])->name('expense-balance.index');
    Route::get('/balance-print/{mounth}/{year}', [ExpenseController::class, 'printBulanBalance'])->name('expense-balance.print-bulan');
    Route::get('/balance-print/{year}', [ExpenseController::class, 'printTahunBalance'])->name('expense-balance.print-tahun');

    Route::get('/equity', [ExpenseController::class, 'indexEquity'])->name('expense-equity.index');
    Route::get('/equity-print/{mounth}/{year}', [ExpenseController::class, 'printBulanEquity'])->name('expense-equity.print-bulan');
    Route::get('/equity-print/{year}', [ExpenseController::class, 'printTahunEquity'])->name('expense-equity.print-tahun');

    Route::get('/cashflow', [ExpenseController::class, 'indexCashflow'])->name('expense-cashflow.index');
    Route::get('/cashflow-print/{mounth}/{year}', [ExpenseController::class, 'printBulanCashflow'])->name('expense-cashflow.print-bulan');
    Route::get('/cashflow-print/{year}', [ExpenseController::class, 'printTahunCashflow'])->name('expense-cashflow.print-tahun');

    // purchase request
    Route::get('/purchase-request', [PurchaseController::class, 'index'])->name('purchase-request.index');
    Route::post('/purchase-request/{id}', [PurchaseController::class, 'store'])->name('purchase-request.store');
    Route::post('/purchase-request-project/{id}', [PurchaseController::class, 'store_project'])->name('purchase-request.store-project');
    Route::get('/purchase-request/{id}', [PurchaseController::class, 'show'])->name('purchase-request.show');
    Route::delete('/purchase-request/delete/{id}', [PurchaseController::class, 'delete'])->name('purchase-request.delete');
    Route::patch('/purchase-request/acc/{id}', [PurchaseController::class, 'acc'])->name('purchase-request.acc');
    Route::patch('/purchase-request/acc-all/{id}', [PurchaseController::class, 'acc_all'])->name('purchase-request.acc-all');
    Route::patch('/purchase-request/delivery/{id}', [PurchaseController::class, 'delivery'])->name('purchase-request.delivery');
    Route::patch('/purchase-request/delivery-all/{id}', [PurchaseController::class, 'delivery_all'])->name('purchase-request.delivery-all');
    Route::get('/purchase-request/done-all/{id}', [PurchaseController::class, 'done_all'])->name('purchase-request.done-all');
    Route::post('/purchase-request/store-done-all/{id}', [PurchaseController::class, 'store_done_all'])->name('purchase-request.store-done-all');
    Route::post('/purchase-request/store-done-all-logistic/{id}', [PurchaseController::class, 'store_done_all_logistic'])->name('purchase-request.store-done-all-logistic');

    // Dashboard Function
    // Ajax Sales Kanan
    Route::get('/dashboard/totalQuotation/{sales}', [DashboardController::class, 'totalQuotationAdmin'])->name('totalQuotation.dashboard');
    Route::get('/dashboard/totalProspect/{sales}', [DashboardController::class, 'totalProspectAdmin'])->name('totalProspect.dashboard');
    Route::get('/dashboard/totalHotProspect/{sales}', [DashboardController::class, 'totalHotProspectAdmin'])->name('totalHotProspect.dashboard');
    Route::get('/dashboard/totalLoss/{sales}', [DashboardController::class, 'totalLossAdmin'])->name('totalLoss.dashboard');
    Route::get('/dashboard/totalPo/{sales}', [DashboardController::class, 'totalPoAdmin'])->name('totalPo.dashboard');
    Route::get('/dashboard/totalTargetPo/{sales}', [DashboardController::class, 'totalTargetPoAdmin'])->name('totalTargetPo.dashboard');
    // Ajax Sales Kiri
    Route::get('/dashboard/filteredLeads/{sales}', [DashboardController::class, 'filteredLeadsAdmin'])->name('filteredLeads.dashboard');
    Route::get('/dashboard/filteredDc/{sales}', [DashboardController::class, 'filteredDcAdmin'])->name('filteredDc.dashboard');
    Route::get('/dashboard/filteredCRM/{sales}', [DashboardController::class, 'filteredCRMAdmin'])->name('filteredCRM.dashboard');
    Route::get('/dashboard/filteredQuote/{sales}', [DashboardController::class, 'filteredQuoteAdmin'])->name('filteredQuote.dashboard');
    Route::get('/dashboard/filteredProspectAdmin/{sales}', [DashboardController::class, 'filteredProspectAdmin'])->name('filteredProspect.dashboard');
    Route::get('/dashboard/filteredAllProspect/{sales}', [DashboardController::class, 'filteredAllProspectAdmin'])->name('filteredAllProspect.dashboard');
    Route::get('/dashboard/filteredTargetLeads/{sales}', [DashboardController::class, 'filteredTargetLeadsAdmin'])->name('filteredTargetLeads.dashboard');
    Route::get('/dashboard/filteredTargetQuote/{sales}', [DashboardController::class, 'filteredTargetQuoteAdmin'])->name('filteredTargetQuote.dashboard');
    Route::get('/dashboard/filteredTargetDc/{sales}', [DashboardController::class, 'filteredTargetDcAdmin'])->name('filteredTargetDc.dashboard');
    Route::get('/dashboard/filteredTargetCRM/{sales}', [DashboardController::class, 'filteredTargetCRMAdmin'])->name('filteredTargetCRM.dashboard');
    Route::get('/dashboard/filteredTargetProspect/{sales}', [DashboardController::class, 'filteredTargetProspectAdmin'])->name('filteredTargetProspect.dashboard');
    Route::get('/dashboard/filteredPercentLeads/{sales}', [DashboardController::class, 'filteredPercentLeadsAdmin'])->name('filteredPercentLeads.dashboard');
    Route::get('/dashboard/filteredPercentQuote/{sales}', [DashboardController::class, 'filteredPercentQuoteAdmin'])->name('filteredPercentQuote.dashboard');
    Route::get('/dashboard/filteredPercentDc/{sales}', [DashboardController::class, 'filteredPercentDcAdmin'])->name('filteredPercentDc.dashboard');
    Route::get('/dashboard/filteredPercentCRM/{sales}', [DashboardController::class, 'filteredPercentCRMAdmin'])->name('filteredPercentCRM.dashboard');
    Route::get('/dashboard/filteredPercentProspectAdmin/{sales}', [DashboardController::class, 'filteredPercentProspectAdmin'])->name('filteredPercentProspect.dashboard');
    // ajax sales online
    Route::get('/dashboard/filteredProduct/{sales}', [DashboardController::class, 'filteredProductAdmin'])->name('filteredProduct.dashboard');
    Route::get('/dashboard/filteredSW/{sales}', [DashboardController::class, 'filteredSWAdmin'])->name('filteredSW.dashboard');
    Route::get('/dashboard/filteredVideo/{sales}', [DashboardController::class, 'filteredVideoAdmin'])->name('filteredVideo.dashboard');
    Route::get('/dashboard/filteredDelivery/{sales}', [DashboardController::class, 'filteredDeliveryAdmin'])->name('filteredDelivery.dashboard');
    Route::get('/dashboard/filteredStat/{sales}', [DashboardController::class, 'filteredStatAdmin'])->name('filteredVideo.dashboard');
    Route::get('/dashboard/filteredCustomer/{sales}', [DashboardController::class, 'filteredCustomerAdmin'])->name('filteredVideo.dashboard');
    Route::get('/dashboard/filteredResponse/{sales}', [DashboardController::class, 'filteredResponseAdmin'])->name('filteredVideo.dashboard');
    Route::get('/dashboard/filteredRating/{sales}', [DashboardController::class, 'filteredRatingAdmin'])->name('filteredVideo.dashboard');

    // Route::get('/dashboard/totalTargetPO/{sales}', [DashboardController::class, 'target'])->name('target.dashboard');
    // Ajax Support
    Route::get('/dashboard/filteredPo/{sales}', [DashboardController::class, 'filteredPoAdmin'])->name('filteredPo.dashboard');
    Route::get('/dashboard/filteredProspect/{sales}', [DashboardController::class, 'filteredProspect'])->name('filteredProspect.dashboard');
    Route::get('/dashboard/filteredProvide/{sales}', [DashboardController::class, 'filteredProvide'])->name('filteredProvide.dashboard');
    Route::get('/dashboard/filteredNotProvide/{sales}', [DashboardController::class, 'filteredNotProvide'])->name('filteredNotProvide.dashboard');
    Route::get('/dashboard/filteredProspectQuote/{sales}', [DashboardController::class, 'filteredProspectedQuotation'])->name('filteredProspect.dashboard');
    Route::get('/dashboard/filteredProspectPO/{sales}', [DashboardController::class, 'filteredProspectedPO'])->name('filteredProspect.dashboard');
    Route::get('/dashboard/totalForecast/{sales}', [DashboardController::class, 'totalForecastAdmin'])->name('totalForecast.dashboard');
    Route::get('/dashboard/totalProspectProspect/{sales}', [DashboardController::class, 'totalProspectedProspect'])->name('totalProspectedProspect.dashboard');
    Route::get('/dashboard/totalProspectPO/{sales}', [DashboardController::class, 'totalProspectedPO'])->name('totalProspectedPO.dashboard');
    Route::get('/dashboard/totalProspectQuote/{sales}', [DashboardController::class, 'totalProspectedQuotation'])->name('totalProspectedQuotation.dashboard');

    Route::get('/dashboard/filteredVisit/{sales}', [DashboardController::class, 'filteredVisitAdmin'])->name('filteredVisit.dashboard');
    Route::get('/dashboard/target/{sales}', [DashboardController::class, 'target'])->name('target.dashboard');
    Route::post('/salesOnline/store', [SalesOnlineController::class, 'store'])->name('store.salon');
    Route::post('/salesOnline/update/{id}', [SalesOnlineController::class, 'update'])->name('update.salon');

    Route::get('/notifactivity', [DashboardController::class, 'notifIndex'])->name('index.notif');
    Route::get('/notifactivity/notif/{date}', [DashboardController::class, 'dateNotif'])->name('date.notif');
    Route::get('/notifactivity/notifAdmin/{date}', [DashboardController::class, 'dateNotifAdmin'])->name('date-admin.notif');
    Route::get('/notifactivity/activity/{date}', [DashboardController::class, 'dateActivity'])->name('date.activity');
    Route::post('/activities/update_calendar', [ActivitiesController::class, 'update_calendar'])->name('date.update_calendar');

    // Payable 
    Route::get('/payable/invoice', [PayableController::class, 'index_invoice'])->name('payable.index_invoice');
    Route::get('/payable/invoice/{id}', [PayableController::class, 'show_invoice'])->name('payable.show_invoice');
    Route::get('/payable/aging', [PayableController::class, 'index_aging'])->name('payable.index_aging');
    Route::get('/payable/aging/{id}', [PayableController::class, 'show_aging'])->name('payable.show_aging');
    Route::get('/payable/receipt', [PayableController::class, 'index_receipt'])->name('payable.index_receipt');
    Route::get('/payable/receipt/{id}', [PayableController::class, 'show_receipt'])->name('payable.show_receipt');
    Route::post('/payable/pph/{id}', [PayableController::class, 'addPph'])->name('payable.addPph');
    Route::post('/payable/date/{id}', [PayableController::class, 'editDate'])->name('payable.editDate');

    // Stock Opname
    Route::get('/stock-opname', [OpnameController::class, 'index'])->name('opname.index');
    Route::post('/stock-opname', [OpnameController::class, 'store'])->name('opname.store');
    Route::get('/stock-opname/{id}', [OpnameController::class, 'show'])->name('opname.show');
    Route::get('/stock-opname-print/{id}', [OpnameController::class, 'show_print'])->name('opname.show_print');
    Route::post('/stock-opname/{id}', [OpnameController::class, 'store_product'])->name('opname.store_product');
    Route::post('/stock-opname/update/{id}', [OpnameController::class, 'update_product'])->name('opname.update_product');
    Route::get('/stock/replacement/{id}', [OpnameController::class, 'stock_replacement'])->name('payable.stock_replacement');
    Route::get('/show/replacement/{id}', [OpnameController::class, 'show_replacement'])->name('payable.show_replacement');

    // Fixed Asset
    Route::resource('/fixed', FixedController::class);

    // Purchase Order
    Route::resource('/purchase', POController::class);
    Route::get('/purchase/print/{id}', [POController::class, 'show_print'])->name('purchase.show_print');
    Route::post('/purchase/pph/{id}', [POController::class, 'add_pph'])->name('purchase.add_pph');
    Route::patch('/purchase/delete-pph/{id}', [POController::class, 'delete_pph'])->name('purchase.delete_pph');

    // Purchase Order
    Route::resource('/product-set', ProductSetController::class);
    Route::post('/product-set/item/{id}', [ProductSetController::class, 'store_item'])->name('product-set.store_item');

    // Database Connection
    Route::get('/db/next-follow/callendar', function () {
        $subquery = DB::table('activities')
            ->join('client as c', 'c.id', '=', 'activities.id_client')
            ->join('issues as i', 'i.id', '=', 'c.id_issues')
            ->join('users as s', 'c.id_sales', '=', 's.id')
            ->select([
                'c.id',
                'i.id as idI',
                'activities.follow_up as start',
                'activities.follow_up as end',
                'c.role as name',
                'activities.note',
                'c.company'
            ])
            ->where('c.id_sales', Auth::id())
            ->orderByDesc('activities.follow_up')
            ->limit(1000);
        $nextFollow = DB::table(DB::raw("({$subquery->toSql()}) as ordered_activities"))
            ->mergeBindings($subquery) // Untuk menggabungkan binding dari subquery
            ->groupBy('ordered_activities.company')
            ->get()
            ->map(function ($activity) {
                // Modifikasi nilai 'calendar' berdasarkan nilai 'name'
                $calendar = match ($activity->name) {
                    'Leads' => 'Business',
                    'Customers' => 'Holiday',
                    default => $activity->name,
                };

                return [
                    'id' => $activity->id,
                    'url' => '',
                    'title' => $activity->company,
                    'start' => $activity->start,
                    'end' => $activity->end,
                    'note' => $activity->note,
                    'allDay' => true,
                    'extendedProps' => [
                        'calendar' => $calendar,
                        'idI' => $activity->idI
                    ]
                ];
            });

        return response()->json(['data' => $nextFollow]);
    });
    Route::get('/db/accounting/callendar', function () {
        $subquery = DB::table(DB::raw('(
                    SELECT p.*, 
                        ROW_NUMBER() OVER (PARTITION BY p.id_quotation ORDER BY p.id ASC) AS payment_order
                    FROM payment p
                ) as pay'))
            ->leftJoin(DB::raw('(
                    SELECT i.*, 
                        ROW_NUMBER() OVER (PARTITION BY i.id_quotation ORDER BY i.id ASC) AS invoice_order
                    FROM invoice i
                ) as inv'), function ($join) {
                $join->on('pay.id_quotation', '=', 'inv.id_quotation')
                    ->on('pay.payment_order', '=', 'inv.invoice_order');
            })
            ->join('quotation as q', 'q.id', '=', 'pay.id_quotation')
            ->join('pic', 'pic.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'pic.id_client')
            ->leftJoin('reminder as r', 'r.id', '=', DB::raw('(SELECT id FROM reminder WHERE reminder.id_payment = pay.id ORDER BY id DESC LIMIT 1)'))
            ->select([
                'pay.id',
                'r.id as idI',
                'pay.amount',
                'pay.due_date',
                'c.company',
                'r.date_fu as start',
                'r.date_fu as end',
                'c.info as name',
                'r.reminder as note',
                'inv.no_invoice'
            ])
            ->whereNotNull('pay.due_date')
            ->orderByDesc('r.date_fu')
            ->orderByDesc('pay.due_date')
            ->limit(1000);
        $nextFollow = DB::table(DB::raw("({$subquery->toSql()}) as ordered_activities"))
            ->mergeBindings($subquery) // Untuk menggabungkan binding dari subquery
            ->groupBy('ordered_activities.no_invoice')
            ->get()
            ->map(function ($activity) {
                // Modifikasi nilai 'calendar' berdasarkan nilai 'name'
                if ($activity->note == NUll) {
                    $calendar = 'Business';
                } else {
                    $calendar = match ($activity->name) {
                        'Reftech' => 'Holiday',
                        'Kojisha' => 'Personal',
                        default => $activity->name,
                    };
                }

                return [
                    'id' => $activity->id,
                    'url' => '',
                    'invoice' => $activity->no_invoice,
                    'company' => $activity->company,
                    'amount' => 'Rp. ' . number_format($activity->amount, 0, ',', '.'),
                    'title' => substr($activity->no_invoice, 0, 3) . ' - ' . $activity->company,
                    'start' => $activity->start ?? $activity->due_date,
                    'end' => $activity->end ?? $activity->due_date,
                    'name' => $activity->name,
                    'note' => $activity->note,
                    'allDay' => true,
                    'extendedProps' => [
                        'calendar' => $calendar,
                        'idI' => $activity->idI
                    ]
                ];
            });

        return response()->json(['data' => $nextFollow]);
    });
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
    Route::get('/db/client/user', function () {
        require_once base_path('app/api/customers/connectionUser.php');
    });
    Route::get('/db/client/reseller', function () {
        require_once base_path('app/api/customers/connectionReseller.php');
    });
    Route::get('/db/client/user/admin', function () {
        require_once base_path('app/api/customers/connectionUserAdmin.php');
    });
    Route::get('/db/client/reseller/admin', function () {
        require_once base_path('app/api/customers/connectionResellerAdmin.php');
    });
    Route::get('/db/crm', function () {
        require_once base_path('app/api/crm/connection.php');
    });
    Route::get('/db/crm/admin', function () {
        require_once base_path('app/api/crm/connectionAdmin.php');
    });
    Route::get('/db/quotation', function () {
        require_once base_path('app/api/quotation/connection.php');
    });
    Route::get('/db/quotation/admin', function () {
        require_once base_path('app/api/quotation/connectionAdmin.php');
    });
    Route::get('/db/quotation/unit', function () {
        require_once base_path('app/api/quotation/connectionUnit.php');
    });
    Route::get('/db/quotation/admin/unit', function () {
        require_once base_path('app/api/quotation/connectionAdminUnit.php');
    });
    Route::get('/db/quotation/archive', function () {
        require_once base_path('app/api/quotation/connectionArchive.php');
    });
    Route::get('/db/quotation/prospect', function () {
        require_once base_path('app/api/quotation/connectionProspect.php');
    });
    Route::get('/db/quotation/prospect/sales', function () {
        require_once base_path('app/api/quotation/connectionProspectSales.php');
    });
    Route::get('/db/quotation/sales/{id}', function ($id) {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->join('client', 'client.id', '=', 'pic.id_client')->whereMonth('estimated_date', $monthNow)->where('quotation.id_sales', $id)->where('quotation.level', '1')->where('quotation.is_primary', '1')->get(['quotation.*', 'client.company']);
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/quotation/mentions/{id}', function ($id) {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->join('client', 'client.id', '=', 'pic.id_client')->whereMonth('estimated_date', $monthNow)->where('quotation.id_sales', $id)->where('quotation.level', '1')->where('quotation.is_primary', '1')->get(['quotation.*', 'client.company']);
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/po/sales/{id}', function ($id) {
        $dateNow = Carbon::now();
        $monthNow = $dateNow->month;
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->join('client', 'client.id', '=', 'pic.id_client')->whereMonth('po_date', $monthNow)->where('quotation.id_sales', $id)->where('status', '100')->orderBy('po_date', 'asc')->where('quotation.level', '1')->where('quotation.is_primary', '1')->get(['quotation.*', 'client.company']);
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/quotation/invoice', function () {
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('invoice', 'invoice.id_quotation', '=', 'quotation.id')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->whereNotNULL('client.npwp')
            ->whereNotNull('quotation.po_file')
            ->whereNull('invoice.no_invoice')
            ->get(['quotation.*', 'client.company', 'users.name']);
        ;
        return response()->json(['data' => $quotation]);
    });
    Route::get('/db/comment/sales', function () {
        $comment = Comment::join('users', 'users.id', '=', 'comment.id_user')
            ->join('change_status', 'change_status.id', '=', 'comment.id_status')
            ->join('quotation', 'quotation.id', '=', 'change_status.id_quotation')
            ->where('comment.level', '1')
            ->where('quotation.id_sales', Auth::user()->id)
            ->get(['comment.*', 'quotation.no_quote', 'users.name', 'quotation.id as id_q', 'change_status.status']);
        return response()->json(['data' => $comment]);
    });
    Route::get('/db/invoice/ppn/reftech', function () {
        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '11')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.no_invoice')
            ->get(['invoice.*', 'client.company', 'users.name', 'quotation.harga_total', 'quotation.po_date']);
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/invoice/nonppn/reftech', function () {
        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->where('invoice.flag', 'Reftech')
            ->where('quotation.tax', '0')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderBy('invoice.no_invoice', 'DESC')
            ->get(['invoice.*', 'client.company', 'users.name', 'quotation.harga_total', 'quotation.po_date']);
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/invoice-ppn/kojisha', function () {
        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->where('invoice.flag', 'Kojisha')
            ->where('quotation.tax', '11')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.no_invoice')
            ->get(['invoice.*', 'client.company', 'users.name', 'quotation.harga_total', 'quotation.po_date']);
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/invoice-nonppn/kojisha', function () {
        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')
            ->where('status', '100')
            ->where('invoice.flag', 'Kojisha')
            ->where('quotation.tax', '0')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.no_invoice')
            ->get(['invoice.*', 'client.company', 'users.name', 'quotation.harga_total', 'quotation.po_date']);
        return response()->json(['data' => $invoice]);
    });

    Route::get('/db/sales/invoice/ar', function () {
        // last payment (umum)
        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.overdue', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last DP
        $lastDP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="DP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last BP
        $lastBP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="BP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last Other (selain DP/BP)
        $lastOther = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type NOT IN ("DP","BP") GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // sum DP / BP level 1
        $sumDP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_dp'))
            ->where('type', 'DP')->where('level', 1)->groupBy('id_quotation');
        $sumBP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_bp'))
            ->where('type', 'BP')->where('level', 1)->groupBy('id_quotation');

        // sum all payments level 1
        $sumPaymentLvl1 = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_payment_level1'))
            ->where('level', 1)
            ->groupBy('id_quotation');

        // count payments per quotation
        $paymentCountSub = DB::table('payment')
            ->select('id_quotation', DB::raw('COUNT(*) as payment_count'))
            ->groupBy('id_quotation');

        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')

            // join last payment umum (dipertahankan)
            ->leftJoinSub($lastPaymentSub, 'pay', fn($join) => $join->on('quotation.id', '=', 'pay.id_quotation'))

            // join last DP/BP/Other (hanya info, tidak dicampur ke outstanding)
            ->leftJoinSub($lastDP, 'dp_last', fn($join) => $join->on('quotation.id', '=', 'dp_last.id_quotation'))
            ->leftJoinSub($lastBP, 'bp_last', fn($join) => $join->on('quotation.id', '=', 'bp_last.id_quotation'))
            ->leftJoinSub($lastOther, 'other_last', fn($join) => $join->on('quotation.id', '=', 'other_last.id_quotation'))

            // join sums DP/BP dan sum level1
            ->leftJoinSub($sumDP, 'dp_sum', fn($join) => $join->on('quotation.id', '=', 'dp_sum.id_quotation'))
            ->leftJoinSub($sumBP, 'bp_sum', fn($join) => $join->on('quotation.id', '=', 'bp_sum.id_quotation'))
            ->leftJoinSub($sumPaymentLvl1, 'pay_sum_lvl1', fn($join) => $join->on('quotation.id', '=', 'pay_sum_lvl1.id_quotation'))

            // join payment count
            ->leftJoinSub($paymentCountSub, 'pay_count', fn($join) => $join->on('quotation.id', '=', 'pay_count.id_quotation'))

            ->where('quotation.status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.date')
            ->select([
                'invoice.*',
                DB::raw("SUBSTRING(invoice.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(invoice.no_po, 1, 10) as short_po"),
                'client.company',
                'client.info as bendera',
                'users.name as name',
                DB::raw("DATE_FORMAT(invoice.date, '%d-%m-%Y') as tanggal"),
                'quotation.harga_total',
                'quotation.po_date',
                'quotation.tax',

                // last payment default (tetap dipertahankan)
                DB::raw('IFNULL(pay.amount,0) as last_payment_amount'),
                DB::raw('pay.type as last_payment_type'),
                DB::raw('pay.level as last_payment_level'),
                DB::raw('pay.due_date as last_due_date'),
                DB::raw('pay.overdue as last_overdue'),

                // last DP
                DB::raw('IFNULL(dp_last.amount,0) as dp_amount'),
                DB::raw('dp_last.level as dp_level'),

                // last BP
                DB::raw('IFNULL(bp_last.amount,0) as bp_amount'),
                DB::raw('bp_last.level as bp_level'),

                // last Other
                DB::raw('IFNULL(other_last.amount,0) as other_amount'),
                DB::raw('other_last.type as other_type'),
                DB::raw('other_last.level as other_level'),

                // sums
                DB::raw('IFNULL(dp_sum.total_dp,0) as total_dp'),
                DB::raw('IFNULL(bp_sum.total_bp,0) as total_bp'),
                DB::raw('IFNULL(pay_sum_lvl1.total_payment_level1,0) as total_payment_level1'),

                // payment count (1 atau lebih)
                DB::raw('IFNULL(pay_count.payment_count,0) as payment_count'),

                // outstanding logic: pertama cek payment_count
                DB::raw("
                            CASE
                -- kalau hanya 1 payment
                WHEN IFNULL(pay_count.payment_count,0) = 1
                    THEN (
                        CASE
                            -- kalau method DP
                            WHEN pay.method = 'DP' THEN 
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total
                                    ELSE quotation.harga_total - IFNULL(pay.amount,0)
                                END

                            -- kalau bukan DP
                            ELSE (
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total 
                                    ELSE 0 
                                END
                            )
                        END
                    )

                -- kalau lebih dari 1 payment
                WHEN IFNULL(pay_count.payment_count,0) > 1
                    THEN (
                        CASE
                            WHEN IFNULL(dp_sum.total_dp,0) = 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total - IFNULL(dp_sum.total_dp,0)
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) > 0 
                                THEN 0
                            ELSE quotation.harga_total
                        END
                    )

                ELSE quotation.harga_total
            END as outstanding
            ")
            ])
            ->get();
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/sales/invoice/reftech', function () {
        // last payment (umum)
        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.overdue', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last DP
        $lastDP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="DP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last BP
        $lastBP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="BP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last Other (selain DP/BP)
        $lastOther = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type NOT IN ("DP","BP") GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // sum DP / BP level 1
        $sumDP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_dp'))
            ->where('type', 'DP')->where('level', 1)->groupBy('id_quotation');
        $sumBP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_bp'))
            ->where('type', 'BP')->where('level', 1)->groupBy('id_quotation');

        // sum all payments level 1
        $sumPaymentLvl1 = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_payment_level1'))
            ->where('level', 1)
            ->groupBy('id_quotation');

        // count payments per quotation
        $paymentCountSub = DB::table('payment')
            ->select('id_quotation', DB::raw('COUNT(*) as payment_count'))
            ->groupBy('id_quotation');

        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')

            // join last payment umum (dipertahankan)
            ->leftJoinSub($lastPaymentSub, 'pay', fn($join) => $join->on('quotation.id', '=', 'pay.id_quotation'))

            // join last DP/BP/Other (hanya info, tidak dicampur ke outstanding)
            ->leftJoinSub($lastDP, 'dp_last', fn($join) => $join->on('quotation.id', '=', 'dp_last.id_quotation'))
            ->leftJoinSub($lastBP, 'bp_last', fn($join) => $join->on('quotation.id', '=', 'bp_last.id_quotation'))
            ->leftJoinSub($lastOther, 'other_last', fn($join) => $join->on('quotation.id', '=', 'other_last.id_quotation'))

            // join sums DP/BP dan sum level1
            ->leftJoinSub($sumDP, 'dp_sum', fn($join) => $join->on('quotation.id', '=', 'dp_sum.id_quotation'))
            ->leftJoinSub($sumBP, 'bp_sum', fn($join) => $join->on('quotation.id', '=', 'bp_sum.id_quotation'))
            ->leftJoinSub($sumPaymentLvl1, 'pay_sum_lvl1', fn($join) => $join->on('quotation.id', '=', 'pay_sum_lvl1.id_quotation'))

            // join payment count
            ->leftJoinSub($paymentCountSub, 'pay_count', fn($join) => $join->on('quotation.id', '=', 'pay_count.id_quotation'))

            ->where('client.info', 'Reftech')
            ->where('quotation.status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.date')
            ->select([
                'invoice.*',
                DB::raw("SUBSTRING(invoice.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(invoice.no_po, 1, 10) as short_po"),
                'client.company',
                'client.info as bendera',
                'users.name as name',
                DB::raw("DATE_FORMAT(invoice.date, '%d-%m-%Y') as tanggal"),
                'quotation.harga_total',
                'quotation.po_date',
                'quotation.tax',

                // last payment default (tetap dipertahankan)
                DB::raw('IFNULL(pay.amount,0) as last_payment_amount'),
                DB::raw('pay.type as last_payment_type'),
                DB::raw('pay.level as last_payment_level'),
                DB::raw('pay.due_date as last_due_date'),
                DB::raw('pay.overdue as last_overdue'),

                // last DP
                DB::raw('IFNULL(dp_last.amount,0) as dp_amount'),
                DB::raw('dp_last.level as dp_level'),

                // last BP
                DB::raw('IFNULL(bp_last.amount,0) as bp_amount'),
                DB::raw('bp_last.level as bp_level'),

                // last Other
                DB::raw('IFNULL(other_last.amount,0) as other_amount'),
                DB::raw('other_last.type as other_type'),
                DB::raw('other_last.level as other_level'),

                // sums
                DB::raw('IFNULL(dp_sum.total_dp,0) as total_dp'),
                DB::raw('IFNULL(bp_sum.total_bp,0) as total_bp'),
                DB::raw('IFNULL(pay_sum_lvl1.total_payment_level1,0) as total_payment_level1'),

                // payment count (1 atau lebih)
                DB::raw('IFNULL(pay_count.payment_count,0) as payment_count'),

                // outstanding logic: pertama cek payment_count
                DB::raw("
                            CASE
                -- kalau hanya 1 payment
                WHEN IFNULL(pay_count.payment_count,0) = 1
                    THEN (
                        CASE
                            -- kalau method DP
                            WHEN pay.method = 'DP' THEN 
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total
                                    ELSE quotation.harga_total - IFNULL(pay.amount,0)
                                END

                            -- kalau bukan DP
                            ELSE (
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total 
                                    ELSE 0 
                                END
                            )
                        END
                    )

                -- kalau lebih dari 1 payment
                WHEN IFNULL(pay_count.payment_count,0) > 1
                    THEN (
                        CASE
                            WHEN IFNULL(dp_sum.total_dp,0) = 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total - IFNULL(dp_sum.total_dp,0)
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) > 0 
                                THEN 0
                            ELSE quotation.harga_total
                        END
                    )

                ELSE quotation.harga_total
            END as outstanding
            ")
            ])
            ->get();
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/sales/invoice/kojisha', function () {
        // last payment (umum)
        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.overdue', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last DP
        $lastDP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="DP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last BP
        $lastBP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="BP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last Other (selain DP/BP)
        $lastOther = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type NOT IN ("DP","BP") GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // sum DP / BP level 1
        $sumDP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_dp'))
            ->where('type', 'DP')->where('level', 1)->groupBy('id_quotation');
        $sumBP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_bp'))
            ->where('type', 'BP')->where('level', 1)->groupBy('id_quotation');

        // sum all payments level 1
        $sumPaymentLvl1 = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_payment_level1'))
            ->where('level', 1)
            ->groupBy('id_quotation');

        // count payments per quotation
        $paymentCountSub = DB::table('payment')
            ->select('id_quotation', DB::raw('COUNT(*) as payment_count'))
            ->groupBy('id_quotation');

        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')

            // join last payment umum (dipertahankan)
            ->leftJoinSub($lastPaymentSub, 'pay', fn($join) => $join->on('quotation.id', '=', 'pay.id_quotation'))

            // join last DP/BP/Other (hanya info, tidak dicampur ke outstanding)
            ->leftJoinSub($lastDP, 'dp_last', fn($join) => $join->on('quotation.id', '=', 'dp_last.id_quotation'))
            ->leftJoinSub($lastBP, 'bp_last', fn($join) => $join->on('quotation.id', '=', 'bp_last.id_quotation'))
            ->leftJoinSub($lastOther, 'other_last', fn($join) => $join->on('quotation.id', '=', 'other_last.id_quotation'))

            // join sums DP/BP dan sum level1
            ->leftJoinSub($sumDP, 'dp_sum', fn($join) => $join->on('quotation.id', '=', 'dp_sum.id_quotation'))
            ->leftJoinSub($sumBP, 'bp_sum', fn($join) => $join->on('quotation.id', '=', 'bp_sum.id_quotation'))
            ->leftJoinSub($sumPaymentLvl1, 'pay_sum_lvl1', fn($join) => $join->on('quotation.id', '=', 'pay_sum_lvl1.id_quotation'))

            // join payment count
            ->leftJoinSub($paymentCountSub, 'pay_count', fn($join) => $join->on('quotation.id', '=', 'pay_count.id_quotation'))

            ->where('client.info', 'Kojisha')
            ->where('quotation.status', '100')
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.date')
            ->select([
                'invoice.*',
                DB::raw("SUBSTRING(invoice.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(invoice.no_po, 1, 10) as short_po"),
                'client.company',
                'client.info as bendera',
                'users.name as name',
                DB::raw("DATE_FORMAT(invoice.date, '%d-%m-%Y') as tanggal"),
                'quotation.harga_total',
                'quotation.po_date',
                'quotation.tax',

                // last payment default (tetap dipertahankan)
                DB::raw('IFNULL(pay.amount,0) as last_payment_amount'),
                DB::raw('pay.type as last_payment_type'),
                DB::raw('pay.level as last_payment_level'),
                DB::raw('pay.due_date as last_due_date'),
                DB::raw('pay.overdue as last_overdue'),

                // last DP
                DB::raw('IFNULL(dp_last.amount,0) as dp_amount'),
                DB::raw('dp_last.level as dp_level'),

                // last BP
                DB::raw('IFNULL(bp_last.amount,0) as bp_amount'),
                DB::raw('bp_last.level as bp_level'),

                // last Other
                DB::raw('IFNULL(other_last.amount,0) as other_amount'),
                DB::raw('other_last.type as other_type'),
                DB::raw('other_last.level as other_level'),

                // sums
                DB::raw('IFNULL(dp_sum.total_dp,0) as total_dp'),
                DB::raw('IFNULL(bp_sum.total_bp,0) as total_bp'),
                DB::raw('IFNULL(pay_sum_lvl1.total_payment_level1,0) as total_payment_level1'),

                // payment count (1 atau lebih)
                DB::raw('IFNULL(pay_count.payment_count,0) as payment_count'),

                // outstanding logic: pertama cek payment_count
                DB::raw("
                            CASE
                -- kalau hanya 1 payment
                WHEN IFNULL(pay_count.payment_count,0) = 1
                    THEN (
                        CASE
                            -- kalau method DP
                            WHEN pay.method = 'DP' THEN 
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total
                                    ELSE quotation.harga_total - IFNULL(pay.amount,0)
                                END

                            -- kalau bukan DP
                            ELSE (
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total 
                                    ELSE 0 
                                END
                            )
                        END
                    )

                -- kalau lebih dari 1 payment
                WHEN IFNULL(pay_count.payment_count,0) > 1
                    THEN (
                        CASE
                            WHEN IFNULL(dp_sum.total_dp,0) = 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total - IFNULL(dp_sum.total_dp,0)
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) > 0 
                                THEN 0
                            ELSE quotation.harga_total
                        END
                    )

                ELSE quotation.harga_total
            END as outstanding
            ")
            ])
            ->get();
        return response()->json(['data' => $invoice]);
    });

    Route::get('/db/sales/invoice/ahmad', function () {
        // last payment (umum)
        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.overdue', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last DP
        $lastDP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="DP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last BP
        $lastBP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="BP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last Other (selain DP/BP)
        $lastOther = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type NOT IN ("DP","BP") GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // sum DP / BP level 1
        $sumDP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_dp'))
            ->where('type', 'DP')->where('level', 1)->groupBy('id_quotation');
        $sumBP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_bp'))
            ->where('type', 'BP')->where('level', 1)->groupBy('id_quotation');

        // sum all payments level 1
        $sumPaymentLvl1 = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_payment_level1'))
            ->where('level', 1)
            ->groupBy('id_quotation');

        // count payments per quotation
        $paymentCountSub = DB::table('payment')
            ->select('id_quotation', DB::raw('COUNT(*) as payment_count'))
            ->groupBy('id_quotation');

        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')

            // join last payment umum (dipertahankan)
            ->leftJoinSub($lastPaymentSub, 'pay', fn($join) => $join->on('quotation.id', '=', 'pay.id_quotation'))

            // join last DP/BP/Other (hanya info, tidak dicampur ke outstanding)
            ->leftJoinSub($lastDP, 'dp_last', fn($join) => $join->on('quotation.id', '=', 'dp_last.id_quotation'))
            ->leftJoinSub($lastBP, 'bp_last', fn($join) => $join->on('quotation.id', '=', 'bp_last.id_quotation'))
            ->leftJoinSub($lastOther, 'other_last', fn($join) => $join->on('quotation.id', '=', 'other_last.id_quotation'))

            // join sums DP/BP dan sum level1
            ->leftJoinSub($sumDP, 'dp_sum', fn($join) => $join->on('quotation.id', '=', 'dp_sum.id_quotation'))
            ->leftJoinSub($sumBP, 'bp_sum', fn($join) => $join->on('quotation.id', '=', 'bp_sum.id_quotation'))
            ->leftJoinSub($sumPaymentLvl1, 'pay_sum_lvl1', fn($join) => $join->on('quotation.id', '=', 'pay_sum_lvl1.id_quotation'))

            // join payment count
            ->leftJoinSub($paymentCountSub, 'pay_count', fn($join) => $join->on('quotation.id', '=', 'pay_count.id_quotation'))

            ->where('quotation.status', '100')
            ->whereIn('quotation.id_sales', [2, 3, 4, 32])
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.date')
            ->select([
                'invoice.*',
                DB::raw("SUBSTRING(invoice.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(invoice.no_po, 1, 10) as short_po"),
                'client.company',
                'client.info as bendera',
                'users.name as name',
                DB::raw("DATE_FORMAT(invoice.date, '%d-%m-%Y') as tanggal"),
                'quotation.harga_total',
                'quotation.po_date',
                'quotation.tax',

                // last payment default (tetap dipertahankan)
                DB::raw('IFNULL(pay.amount,0) as last_payment_amount'),
                DB::raw('pay.type as last_payment_type'),
                DB::raw('pay.level as last_payment_level'),
                DB::raw('pay.due_date as last_due_date'),
                DB::raw('pay.overdue as last_overdue'),

                // last DP
                DB::raw('IFNULL(dp_last.amount,0) as dp_amount'),
                DB::raw('dp_last.level as dp_level'),

                // last BP
                DB::raw('IFNULL(bp_last.amount,0) as bp_amount'),
                DB::raw('bp_last.level as bp_level'),

                // last Other
                DB::raw('IFNULL(other_last.amount,0) as other_amount'),
                DB::raw('other_last.type as other_type'),
                DB::raw('other_last.level as other_level'),

                // sums
                DB::raw('IFNULL(dp_sum.total_dp,0) as total_dp'),
                DB::raw('IFNULL(bp_sum.total_bp,0) as total_bp'),
                DB::raw('IFNULL(pay_sum_lvl1.total_payment_level1,0) as total_payment_level1'),

                // payment count (1 atau lebih)
                DB::raw('IFNULL(pay_count.payment_count,0) as payment_count'),

                // outstanding logic: pertama cek payment_count
                DB::raw("
                            CASE
                -- kalau hanya 1 payment
                WHEN IFNULL(pay_count.payment_count,0) = 1
                    THEN (
                        CASE
                            -- kalau method DP
                            WHEN pay.method = 'DP' THEN 
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total
                                    ELSE quotation.harga_total - IFNULL(pay.amount,0)
                                END

                            -- kalau bukan DP
                            ELSE (
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total 
                                    ELSE 0 
                                END
                            )
                        END
                    )

                -- kalau lebih dari 1 payment
                WHEN IFNULL(pay_count.payment_count,0) > 1
                    THEN (
                        CASE
                            WHEN IFNULL(dp_sum.total_dp,0) = 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total - IFNULL(dp_sum.total_dp,0)
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) > 0 
                                THEN 0
                            ELSE quotation.harga_total
                        END
                    )

                ELSE quotation.harga_total
            END as outstanding
            ")
            ])
            ->get();
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/sales/invoice/rayi', function () {
        // last payment (umum)
        $lastPaymentSub = DB::table('payment as p1')
            ->select('p1.id', 'p1.id_quotation', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.overdue', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last DP
        $lastDP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="DP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last BP
        $lastBP = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type="BP" GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // last Other (selain DP/BP)
        $lastOther = DB::table('payment as p1')
            ->select('p1.id_quotation', 'p1.id', 'p1.amount', 'p1.type', 'p1.level', 'p1.due_date', 'p1.method', 'p1.created_at')
            ->join(
                DB::raw('(SELECT id_quotation, MAX(id) as max_id FROM payment WHERE type NOT IN ("DP","BP") GROUP BY id_quotation) as p2'),
                'p1.id',
                '=',
                'p2.max_id'
            );

        // sum DP / BP level 1
        $sumDP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_dp'))
            ->where('type', 'DP')->where('level', 1)->groupBy('id_quotation');
        $sumBP = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_bp'))
            ->where('type', 'BP')->where('level', 1)->groupBy('id_quotation');

        // sum all payments level 1
        $sumPaymentLvl1 = DB::table('payment')
            ->select('id_quotation', DB::raw('SUM(amount) as total_payment_level1'))
            ->where('level', 1)
            ->groupBy('id_quotation');

        // count payments per quotation
        $paymentCountSub = DB::table('payment')
            ->select('id_quotation', DB::raw('COUNT(*) as payment_count'))
            ->groupBy('id_quotation');

        $invoice = Invoice::join('quotation', 'quotation.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->join('users', 'users.id', '=', 'quotation.id_sales')

            // join last payment umum (dipertahankan)
            ->leftJoinSub($lastPaymentSub, 'pay', fn($join) => $join->on('quotation.id', '=', 'pay.id_quotation'))

            // join last DP/BP/Other (hanya info, tidak dicampur ke outstanding)
            ->leftJoinSub($lastDP, 'dp_last', fn($join) => $join->on('quotation.id', '=', 'dp_last.id_quotation'))
            ->leftJoinSub($lastBP, 'bp_last', fn($join) => $join->on('quotation.id', '=', 'bp_last.id_quotation'))
            ->leftJoinSub($lastOther, 'other_last', fn($join) => $join->on('quotation.id', '=', 'other_last.id_quotation'))

            // join sums DP/BP dan sum level1
            ->leftJoinSub($sumDP, 'dp_sum', fn($join) => $join->on('quotation.id', '=', 'dp_sum.id_quotation'))
            ->leftJoinSub($sumBP, 'bp_sum', fn($join) => $join->on('quotation.id', '=', 'bp_sum.id_quotation'))
            ->leftJoinSub($sumPaymentLvl1, 'pay_sum_lvl1', fn($join) => $join->on('quotation.id', '=', 'pay_sum_lvl1.id_quotation'))

            // join payment count
            ->leftJoinSub($paymentCountSub, 'pay_count', fn($join) => $join->on('quotation.id', '=', 'pay_count.id_quotation'))

            ->where('quotation.status', '100')
            ->whereIn('quotation.id_sales', [1, 16, 23])
            ->whereNotNull('quotation.po_file')
            ->whereNotNull('invoice.no_invoice')
            ->orderByDesc('invoice.date')
            ->select([
                'invoice.*',
                DB::raw("SUBSTRING(invoice.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(invoice.no_po, 1, 10) as short_po"),
                'client.company',
                'client.info as bendera',
                'users.name as name',
                DB::raw("DATE_FORMAT(invoice.date, '%d-%m-%Y') as tanggal"),
                'quotation.harga_total',
                'quotation.po_date',
                'quotation.tax',

                // last payment default (tetap dipertahankan)
                DB::raw('IFNULL(pay.amount,0) as last_payment_amount'),
                DB::raw('pay.type as last_payment_type'),
                DB::raw('pay.level as last_payment_level'),
                DB::raw('pay.due_date as last_due_date'),
                DB::raw('pay.overdue as last_overdue'),

                // last DP
                DB::raw('IFNULL(dp_last.amount,0) as dp_amount'),
                DB::raw('dp_last.level as dp_level'),

                // last BP
                DB::raw('IFNULL(bp_last.amount,0) as bp_amount'),
                DB::raw('bp_last.level as bp_level'),

                // last Other
                DB::raw('IFNULL(other_last.amount,0) as other_amount'),
                DB::raw('other_last.type as other_type'),
                DB::raw('other_last.level as other_level'),

                // sums
                DB::raw('IFNULL(dp_sum.total_dp,0) as total_dp'),
                DB::raw('IFNULL(bp_sum.total_bp,0) as total_bp'),
                DB::raw('IFNULL(pay_sum_lvl1.total_payment_level1,0) as total_payment_level1'),

                // payment count (1 atau lebih)
                DB::raw('IFNULL(pay_count.payment_count,0) as payment_count'),

                // outstanding logic: pertama cek payment_count
                DB::raw("
                            CASE
                -- kalau hanya 1 payment
                WHEN IFNULL(pay_count.payment_count,0) = 1
                    THEN (
                        CASE
                            -- kalau method DP
                            WHEN pay.method = 'DP' THEN 
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total
                                    ELSE quotation.harga_total - IFNULL(pay.amount,0)
                                END

                            -- kalau bukan DP
                            ELSE (
                                CASE 
                                    WHEN IFNULL(pay.level,0) = 0 THEN quotation.harga_total 
                                    ELSE 0 
                                END
                            )
                        END
                    )

                -- kalau lebih dari 1 payment
                WHEN IFNULL(pay_count.payment_count,0) > 1
                    THEN (
                        CASE
                            WHEN IFNULL(dp_sum.total_dp,0) = 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) = 0 
                                THEN quotation.harga_total - IFNULL(dp_sum.total_dp,0)
                            WHEN IFNULL(dp_sum.total_dp,0) > 0 AND IFNULL(bp_sum.total_bp,0) > 0 
                                THEN 0
                            ELSE quotation.harga_total
                        END
                    )

                ELSE quotation.harga_total
            END as outstanding
            ")
            ])
            ->get();
        return response()->json(['data' => $invoice]);
    });
    Route::get('/db/sales/invoice/escrow', function () {
        $invoice = Invoice::join('quotation as q', 'q.id', '=', 'invoice.id_quotation')
            ->join('pic', 'pic.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'pic.id_client')
            ->join('users as u', 'u.id', '=', 'q.id_sales')
            ->join('payment as p', 'p.id_quotation', '=', 'q.id')
            ->whereIn('u.id', ['16', '23'])
            ->where('p.method', 'Escrow')
            ->select([
                'invoice.*',
                DB::raw("SUBSTRING(invoice.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(invoice.no_po, 1, 10) as short_po"),
                'c.company',
                'c.info as bendera',
                'u.name as name',
                DB::raw("DATE_FORMAT(invoice.date, '%d-%m-%Y') as tanggal"),
                'q.harga_total',
                'q.po_date',
                'q.tax',
            ])->get();
        return response()->json(['data' => $invoice]);
    });


    Route::get('/db/payment/receipt/ar', function () {
        $payment = Payment::with([
            'quotation.payment',
            'quotation.invoice' => function ($i) {
                $i->orderBy('id');
            },
            'quotation.pic.client'
        ])
            // ->whereYear('payment.created_at', Carbon::now()->year)
            ->orderByRaw("
        CASE 
            WHEN level = 0 AND file IS NOT NULL THEN 1
            WHEN level = 0 AND file IS NULL THEN 2
            WHEN level = 1 THEN 3
            ELSE 4
        END
    ")
            ->orderBy('payment.created_at', 'desc')
            ->get();

        $payment = $payment->map(function ($pay) {
            $totalPayment = $pay->quotation?->payment->sum('amount') ?? 0;

            if ($pay->type === 'BP') {
                $invoice = $pay->quotation?->invoice->where('type', 'BP')->first();
            } else {
                $invoice = $pay->quotation?->invoice->first();
            }

            $title = (($pay->quotation?->harga_total ?? 0) - $totalPayment === 0)
                ? 'Full paid'
                : 'Partial';

            return [
                'id' => $pay->id,
                'no_receipt' => '#RCPT-' . $pay->id,
                'date' => $pay->created_at?->format('d-m-Y'),
                'no_invoice' => substr($invoice?->no_invoice, 0, 3) ?? '-',
                'company' => $pay->quotation?->pic?->client?->company ?? '-',
                'flag' => $pay->quotation?->pic?->client?->info ?? '-',
                'name' => $pay->quotation?->sales?->name ?? '-',
                'amount' => $pay->amount,
                'level' => $pay->level,
                'total_payment' => $totalPayment,
                'sisa' => ($pay->quotation?->harga_total ?? 0) - $totalPayment,
                'method' => $pay->method,
                'date_confirm' => $pay->date_confirm,
                'type' => $pay->type,
                'file' => $pay->file,
                'title' => $title,
            ];
        });

        return response()->json(['data' => $payment]);
    });
    Route::get('/db/aging/report/ar', function () {
        $payment = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            // join subquery reminder terakhir
            ->leftJoin(DB::raw('(
        SELECT r1.*
        FROM reminder r1
        INNER JOIN (
            SELECT id_payment, MAX(created_at) AS last_reminder
            FROM reminder
            GROUP BY id_payment
        ) r2 ON r1.id_payment = r2.id_payment AND r1.created_at = r2.last_reminder
    ) as r'), 'r.id_payment', '=', 'payment.id')
            ->where('payment.type', 'Tempo')
            ->whereNot('payment.level', 1)
            ->orderByRaw('payment.due_date IS NULL, payment.due_date ASC')
            ->groupBy('payment.id')
            ->select(
                'payment.id',
                'payment.amount',
                'payment.overdue',
                DB::raw("DATE_FORMAT(payment.due_date, '%d-%m-%Y') as due_date"),
                'i.no_invoice',
                DB::raw("SUBSTRING(i.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(i.no_po, 1, 10) as short_po"),
                'u.name',
                DB::raw("DATE_FORMAT(i.date, '%d-%m-%Y') as date"),
                'i.no_po',
                'c.company',
                'c.info',
                'q.harga_total',
                'q.tax',
                // ambil kolom dari reminder terakhir
                // 'r.date as reminder_date',
                DB::raw("DATE_FORMAT(r.date, '%d-%m-%Y') as reminder_date"),
                'r.status as reminder_status',
                'r.reminder as reminder_note'
            )
            ->get()
            ->map(function ($row) {
                $due = Carbon::parse($row->due_date);
                $today = Carbon::today();
                $diff = $today->diffInDays($due, false);
                $row->diff = $diff;
                if ($diff > 0) {
                    $row->due_status = $diff . " days left";
                } elseif ($diff == 0) {
                    $row->due_status = "Today";
                } else {
                    $row->due_status = abs($diff) . " days overdue";
                }
                return $row;
            });


        return response()->json(['data' => $payment]);
    });
    Route::get('/db/aging/report/reftech', function () {
        $payment = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->leftJoin(DB::raw('(
        SELECT r1.*
        FROM reminder r1
        INNER JOIN (
            SELECT id_payment, MAX(created_at) AS last_reminder
            FROM reminder
            GROUP BY id_payment
        ) r2 ON r1.id_payment = r2.id_payment AND r1.created_at = r2.last_reminder
    ) as r'), 'r.id_payment', '=', 'payment.id')
            ->where('c.info', 'Reftech')
            ->where('payment.type', 'Tempo')
            ->whereNot('payment.level', 1)
            ->orderByRaw('payment.due_date IS NULL, payment.due_date ASC')
            ->groupBy('payment.id')
            ->select(
                'payment.id',
                'payment.amount',
                'payment.overdue',
                DB::raw("DATE_FORMAT(payment.due_date, '%d-%m-%Y') as due_date"),
                'i.no_invoice',
                DB::raw("SUBSTRING(i.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(i.no_po, 1, 10) as short_po"),
                'u.name',
                DB::raw("DATE_FORMAT(i.date, '%d-%m-%Y') as date"),
                'i.no_po',
                'c.company',
                'c.info',
                'q.harga_total',
                'q.tax',
                DB::raw("DATE_FORMAT(r.date, '%d-%m-%Y') as reminder_date"),
                'r.status as reminder_status',
                'r.reminder as reminder_note'
            )
            ->get()
            ->map(function ($row) {
                $due = Carbon::parse($row->due_date);
                $today = Carbon::today();
                $diff = $today->diffInDays($due, false);
                $row->diff = $diff;

                if ($diff > 0) {
                    $row->due_status = $diff . " days left";
                } elseif ($diff == 0) {
                    $row->due_status = "Today";
                } else {
                    $row->due_status = abs($diff) . " days overdue";
                }

                return $row;
            });

        return response()->json(['data' => $payment]);
    });
    Route::get('/db/aging/report/kojisha', function () {
        $payment = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->leftJoin(DB::raw('(
        SELECT r1.*
        FROM reminder r1
        INNER JOIN (
            SELECT id_payment, MAX(created_at) AS last_reminder
            FROM reminder
            GROUP BY id_payment
        ) r2 ON r1.id_payment = r2.id_payment AND r1.created_at = r2.last_reminder
    ) as r'), 'r.id_payment', '=', 'payment.id')
            ->where('c.info', 'Kojisha')
            ->where('payment.type', 'Tempo')
            ->whereNot('payment.level', 1)
            ->orderByRaw('payment.due_date IS NULL, payment.due_date ASC')
            ->groupBy('payment.id')
            ->select(
                'payment.id',
                'payment.amount',
                'payment.overdue',
                DB::raw("DATE_FORMAT(payment.due_date, '%d-%m-%Y') as due_date"),
                'i.no_invoice',
                DB::raw("SUBSTRING(i.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(i.no_po, 1, 10) as short_po"),
                'u.name',
                DB::raw("DATE_FORMAT(i.date, '%d-%m-%Y') as date"),
                'i.no_po',
                'c.company',
                'c.info',
                'q.harga_total',
                'q.tax',
                DB::raw("DATE_FORMAT(r.date, '%d-%m-%Y') as reminder_date"),
                'r.status as reminder_status',
                'r.reminder as reminder_note'
            )
            ->get()
            ->map(function ($row) {
                $due = Carbon::parse($row->due_date);
                $today = Carbon::today();
                $diff = $today->diffInDays($due, false);
                $row->diff = $diff;

                if ($diff > 0) {
                    $row->due_status = $diff . " days left";
                } elseif ($diff == 0) {
                    $row->due_status = "Today";
                } else {
                    $row->due_status = abs($diff) . " days overdue";
                }

                return $row;
            });

        return response()->json(['data' => $payment]);
    });
    Route::get('/db/aging/report/ahmad', function () {
        $payment = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->leftJoin(DB::raw('(
        SELECT r1.*
        FROM reminder r1
        INNER JOIN (
            SELECT id_payment, MAX(created_at) AS last_reminder
            FROM reminder
            GROUP BY id_payment
        ) r2 ON r1.id_payment = r2.id_payment AND r1.created_at = r2.last_reminder
    ) as r'), 'r.id_payment', '=', 'payment.id')
            ->whereIn('u.id', [2, 3, 4, 32])
            ->where('payment.type', 'Tempo')
            ->whereNot('payment.level', 1)
            ->orderByRaw('payment.due_date IS NULL, payment.due_date ASC')
            ->groupBy('payment.id')
            ->select(
                'payment.id',
                'payment.amount',
                'payment.overdue',
                DB::raw("DATE_FORMAT(payment.due_date, '%d-%m-%Y') as due_date"),
                'i.no_invoice',
                DB::raw("SUBSTRING(i.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(i.no_po, 1, 10) as short_po"),
                'u.name',
                DB::raw("DATE_FORMAT(i.date, '%d-%m-%Y') as date"),
                'i.no_po',
                'c.company',
                'c.info',
                'q.harga_total',
                'q.tax',
                DB::raw("DATE_FORMAT(r.date, '%d-%m-%Y') as reminder_date"),
                'r.status as reminder_status',
                'r.reminder as reminder_note'
            )
            ->get()
            ->map(function ($row) {
                $due = Carbon::parse($row->due_date);
                $today = Carbon::today();
                $diff = $today->diffInDays($due, false);
                $row->diff = $diff;

                if ($diff > 0) {
                    $row->due_status = $diff . " days left";
                } elseif ($diff == 0) {
                    $row->due_status = "Today";
                } else {
                    $row->due_status = abs($diff) . " days overdue";
                }

                return $row;
            });

        return response()->json(['data' => $payment]);
    });
    Route::get('/db/aging/report/rayi', function () {
        $payment = Payment::join('quotation as q', 'q.id', '=', 'payment.id_quotation')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->leftJoin(DB::raw('(
        SELECT r1.*
        FROM reminder r1
        INNER JOIN (
            SELECT id_payment, MAX(created_at) AS last_reminder
            FROM reminder
            GROUP BY id_payment
        ) r2 ON r1.id_payment = r2.id_payment AND r1.created_at = r2.last_reminder
    ) as r'), 'r.id_payment', '=', 'payment.id')
            ->whereIn('u.id', [1, 16, 23])
            ->where('payment.type', 'Tempo')
            ->whereNot('payment.level', 1)
            ->orderByRaw('payment.due_date IS NULL, payment.due_date ASC')
            ->groupBy('payment.id')
            ->select(
                'payment.id',
                'payment.amount',
                'payment.overdue',
                DB::raw("DATE_FORMAT(payment.due_date, '%d-%m-%Y') as due_date"),
                'i.no_invoice',
                DB::raw("SUBSTRING(i.no_invoice, 1, 12) as short_invoice"),
                DB::raw("SUBSTRING(i.no_po, 1, 10) as short_po"),
                'u.name',
                DB::raw("DATE_FORMAT(i.date, '%d-%m-%Y') as date"),
                'i.no_po',
                'c.company',
                'c.info',
                'q.harga_total',
                'q.tax',
                DB::raw("DATE_FORMAT(r.date, '%d-%m-%Y') as reminder_date"),
                'r.status as reminder_status',
                'r.reminder as reminder_note'
            )
            ->get()
            ->map(function ($row) {
                $due = Carbon::parse($row->due_date);
                $today = Carbon::today();
                $diff = $today->diffInDays($due, false);
                $row->diff = $diff;

                if ($diff > 0) {
                    $row->due_status = $diff . " days left";
                } elseif ($diff == 0) {
                    $row->due_status = "Today";
                } else {
                    $row->due_status = abs($diff) . " days overdue";
                }

                return $row;
            });

        return response()->json(['data' => $payment]);
    });
    Route::get('/db/po', function () {
        require_once base_path('app/api/po/connection.php');
    });
    Route::get('/db/po/admin', function () {
        require_once base_path('app/api/po/connectionAdmin.php');
    });
    Route::get('/db/hot_prospect', function () {
        require_once base_path('app/api/prospect/connection.php');
    });
    Route::get('/db/hot_prospect/sales', function () {
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
    Route::get('/db/unit', function () {
        require_once base_path('app/api/product/connectionUnit.php');
    });
    Route::get('/db/unit/second', function () {
        require_once base_path('app/api/product/connectionUnitSecond.php');
    });
    Route::get('/db/sales/unit', function () {
        require_once base_path('app/api/product/connectionSalesUnit.php');
    });
    Route::get('/db/sales/unit/second', function () {
        require_once base_path('app/api/product/connectionSalesUnitSecond.php');
    });
    Route::get('/db/unit/global', function () {
        require_once base_path('app/api/product/connectionUnitGlobal.php');
    });
    Route::get('/db/sales/unit/global', function () {
        require_once base_path('app/api/product/connectionSalesUnitGlobal.php');
    });
    Route::get('/db/unit/global/dryer', function () {
        require_once base_path('app/api/product/connectionUnitDryerGlobal.php');
    });
    Route::get('/db/sales/unit/global/dryer', function () {
        require_once base_path('app/api/product/connectionSalesUnitDryerGlobal.php');
    });
    Route::get('/db/product/master', function () {
        require_once base_path('app/api/product/master/connection.php');
    });
    Route::get('/db/product/stock', function () {
        require_once base_path('app/api/product/stock/connection.php');
    });
    Route::get('/db/product/serial/{id}', function ($id) {
        // Menggunakan Eloquent untuk mengambil data serial_product berdasarkan id
        $serialProduct = SerialProduct::where('id_product', $id)->get();
        // Mengembalikan data dalam bentuk JSON
        return response()->json(['data' => $serialProduct]);
    });
    Route::get('/db/product/quotation/{id}', function ($id) {
        $quotation = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')
            ->where('pic.id_client', $id)
            ->where('quotation.level', '1')
            ->where('quotation.is_primary', '1')
            ->get('quotation.*');
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
    Route::get('/db/product/in/logistik', function () {
        $products = DB::table('product_in as p')
            ->select('p.*', DB::raw('SUM(d.qty) as total_qty'))
            ->leftJoin('detail_product_in as d', 'd.id_product_in', '=', 'p.id')
            // ->where('p.info', 'Lokal')
            ->whereNull('p.invoice')
            ->groupBy('p.id')
            ->get();
        return response()->json(['data' => $products]);
    });
    Route::get('/db/product/in/logistik/lokal', function () {
        $products = DB::table('product_in as p')
            ->select('p.*', DB::raw('SUM(d.qty) as total_qty'))
            ->leftJoin('detail_product_in as d', 'd.id_product_in', '=', 'p.id')
            ->where('p.info', 'Lokal')
            ->whereNull('p.invoice')
            ->groupBy('p.id')
            ->get();
        return response()->json(['data' => $products]);
    });
    Route::get('/db/product/in/logistik/import', function () {
        $products = DB::table('product_in as p')
            ->select('p.*', DB::raw('SUM(d.qty) as total_qty'))
            ->leftJoin('detail_product_in as d', 'd.id_product_in', '=', 'p.id')
            ->where('p.info', 'Import')
            ->whereNull('p.invoice')
            ->groupBy('p.id')
            ->get();
        return response()->json(['data' => $products]);
    });
    Route::get('/db/product/in/lokal', function () {
        $products = DB::table('product_in as p')
            ->select(
                'p.*',
                DB::raw("CONCAT(pr.commodity, ' - ', dp.replacement) AS product"),
                DB::raw("CONCAT(d.qty, ' ', pr.unit) AS qty"),
                DB::raw("s.supplier AS supplier_name")
            )
            ->leftJoin('supplier as s', 'p.id_supplier', '=', 's.id')
            ->leftJoin('detail_product_in as d', 'd.id_product_in', '=', 'p.id')
            ->leftJoin('detail_product as dp', 'd.id_detail_product', '=', 'dp.id')
            ->leftJoin('product as pr', 'dp.id_product', '=', 'pr.id')
            ->where('p.info', 'Lokal')
            ->whereNotNull('p.invoice')
            ->groupBy('p.id')
            ->get();
        return response()->json(['data' => $products]);
    });
    Route::get('/db/product/in/import', function () {
        $products = DB::table('product_in as p')
            ->select(
                'p.*',
                DB::raw("CONCAT(pr.commodity, ' - ', dp.replacement) AS product"),
                DB::raw("CONCAT(d.qty, ' ', pr.unit) AS qty"),
                DB::raw("s.supplier AS supplier_name")
            )
            ->leftJoin('supplier as s', 'p.id_supplier', '=', 's.id')
            ->leftJoin('detail_product_in as d', 'd.id_product_in', '=', 'p.id')
            ->leftJoin('detail_product as dp', 'd.id_detail_product', '=', 'dp.id')
            ->leftJoin('product as pr', 'dp.id_product', '=', 'pr.id')
            ->where('p.info', 'Import')
            ->whereNotNull('p.invoice')
            ->groupBy('p.id')
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
    Route::get('/db/product/quotation/detail/{id}', function ($id) {
        $products = DB::table('quotation as q')
            ->select('q.id', 'q.no_quote', 'q.estimated_date', 'q.status', 'sp.pn', 'dq.price', DB::raw('CONCAT(COALESCE(dq.qty, 0), " ", COALESCE(dq.info_qty, "")) AS qty'))
            ->leftJoin('detail_quotation as dq', 'q.id', '=', 'dq.id_quotation')
            ->leftJoin('serial_product as sp', 'sp.id', '=', 'dq.id_equivalent')
            ->where('sp.id_product', $id)
            ->where('q.level', '1')
            ->where('q.is_primary', '1')
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND u.id = ' . $sales->id . ') AS total'), DB::raw('(SELECT COALESCE(SUM(q.nett), 0) FROM quotation AS q 
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
        AND q.level = "1"
        AND q.is_primary = "1"
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND u.id = ' . $sales->id . ') AS quote'))
            ->get();
        return response()->json(['data' => $data]);
    });

    Route::get('/db/sales/overview-prospect/{id}', function ($id) {
        $sales = User::find($id);
        $data = DB::table('sales_reports AS s')
            ->select('s.*', DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND q.id_support = ' . $id . ') AS total'), DB::raw('(SELECT COALESCE(SUM(q.nett), 0) FROM quotation AS q 
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND q.id_support = ' . $id . ') AS price'), DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND q.id_support = ' . $id . ') AS quote'))
            ->get();
        return response()->json(['data' => $data]);
    });
    // Detail Overview
    Route::get('/db/overview/call/{sales}/{date}', function ($sales, $date) {
        $dateRep = "01-" . $date;
        $dateCarbon = Carbon::createFromFormat('d-m-Y', $dateRep);

        $month = $dateCarbon->month;
        $year = $dateCarbon->year;
        $data = Activities::join('client', 'activities.id_client', '=', 'client.id')->where('client.id_sales', $sales)->whereIn('name', ['Daily Call', 'Follow Up'])->whereMonth('date', $month)->whereYear('date', $year)->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/overview/crm/{sales}/{date}', function ($sales, $date) {
        $dateRep = "01-" . $date;
        $dateCarbon = Carbon::createFromFormat('d-m-Y', $dateRep);

        $month = $dateCarbon->month;
        $year = $dateCarbon->year;
        $data = Activities::join('client', 'activities.id_client', '=', 'client.id')->where('client.id_sales', $sales)->where('name', 'CRM')->whereMonth('date', $month)->whereYear('date', $year)->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/overview/quotation/{sales}/{date}', function ($sales, $date) {
        $dateRep = "01-" . $date;
        $dateCarbon = Carbon::createFromFormat('d-m-Y', $dateRep);

        $month = $dateCarbon->month;
        $year = $dateCarbon->year;
        $data = Quotation::join('pic', 'quotation.id_pic', '=', 'pic.id')->join('client', 'pic.id_client', '=', 'client.id')->where('level', '1')->where('is_primary', '1')->where('quotation.id_sales', $sales)->whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->get(['no_quote', 'client.company', 'nett', 'title', 'estimated_date', 'status', 'quotation.note', 'quotation.id']);
        return response()->json(['data' => $data]);
    });
    Route::get('/db/overview/loss/{sales}/{date}', function ($sales, $date) {
        $dateRep = "01-" . $date;
        $dateCarbon = Carbon::createFromFormat('d-m-Y', $dateRep);

        $month = $dateCarbon->month;
        $year = $dateCarbon->year;
        $data = Quotation::join('pic', 'quotation.id_pic', '=', 'pic.id')
            ->join('client', 'pic.id_client', '=', 'client.id')
            ->where('quotation.id_sales', $sales)
            ->where('quotation.status', '0')
            ->where('level', '1')->where('is_primary', '1')
            ->whereMonth('estimated_date', $month)
            ->whereYear('estimated_date', $year)
            ->get(['no_quote', 'client.company', 'nett', 'title', 'estimated_date', 'status', 'quotation.note', 'quotation.id']);

        $totalNett = Quotation::whereMonth('estimated_date', $month)->whereYear('estimated_date', $year)->where('status', '0')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedTotalNett = number_format($totalNett, 0, ',', '.');
        return response()->json([
            'data' => $data,
            'total_nett' => $formattedTotalNett
        ]);
    });
    Route::get('/db/overview/po/{sales}/{date}', function ($sales, $date) {
        $dateRep = "01-" . $date;
        $dateCarbon = Carbon::createFromFormat('d-m-Y', $dateRep);

        $month = $dateCarbon->month;
        $year = $dateCarbon->year;
        $data = Quotation::join('pic', 'quotation.id_pic', '=', 'pic.id')
            ->join('client', 'pic.id_client', '=', 'client.id')
            ->where('quotation.id_sales', $sales)
            ->where('quotation.status', '100')
            ->where('level', '1')->where('is_primary', '1')
            ->whereMonth('po_date', $month)
            ->whereYear('po_date', $year)
            ->get(['no_quote', 'client.company', 'nett', 'title', 'po_date', 'status', 'quotation.note', 'quotation.id']);

        $totalNett = Quotation::whereMonth('po_date', $month)->whereYear('po_date', $year)->where('status', '100')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedTotalNett = number_format($totalNett, 0, ',', '.');
        return response()->json([
            'data' => $data,
            'total_nett' => $formattedTotalNett
        ]);
    });
    Route::get('/db/overview/po-prospect/{sales}/{date}', function ($sales, $date) {
        $dateRep = "01-" . $date;
        $dateCarbon = Carbon::createFromFormat('d-m-Y', $dateRep);

        $month = $dateCarbon->month;
        $year = $dateCarbon->year;
        $data = Quotation::join('pic', 'quotation.id_pic', '=', 'pic.id')
            ->join('client', 'pic.id_client', '=', 'client.id')
            ->join('users as s', 's.id', '=', 'quotation.id_sales')
            ->where('quotation.id_support', $sales)
            ->where('quotation.status', '100')
            ->where('quotation.level', '1')->where('is_primary', '1')
            ->whereMonth('po_date', $month)
            ->whereYear('po_date', $year)
            ->get(['s.name', 'no_quote', 'client.company', 'nett', 'title', 'po_date', 'status', 'quotation.note', 'quotation.id']);

        $totalNett = Quotation::whereMonth('po_date', $month)->whereYear('po_date', $year)->where('status', '100')->where('id_sales', $sales)->where('level', '1')->where('is_primary', '1')->sum('nett');
        $formattedTotalNett = number_format($totalNett, 0, ',', '.');
        return response()->json([
            'data' => $data,
            'total_nett' => $formattedTotalNett
        ]);
    });

    Route::get('/db/client/po-history/{id}', function ($id) {
        $data = Quotation::join('pic', 'pic.id', '=', 'quotation.id_pic')->where('level', '1')->where('is_primary', '1')->where('quotation.status', '100')->where('pic.id_client', $id)->get('quotation.*');
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
            ->join('unit', 'unit.id', '=', 'machine.id_unit')
            ->join('serial_product as s', 'unit.id', '=', 's.id_product')
            ->where('pic.id_client', $id)
            ->where('reports.type', 'Service')
            ->select(
                'reports.*',
                'users.name',
                DB::raw("CONCAT(s.brand, ' ', unit.sku) AS brand_type")
            )
            ->groupBy('reports.id', 'unit.id')
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/client/visit-history/{id}', function ($id) {
        $data = Reports::join('pic', 'pic.id', '=', 'reports.id_pic')
            ->join('users', 'users.id', '=', 'reports.id_technician')
            ->join('machine', 'machine.id', '=', 'reports.id_machine')
            ->join('unit', 'unit.id', '=', 'machine.id_unit')
            ->join('serial_product as s', 'unit.id', '=', 's.id_product')
            ->where('pic.id_client', $id)
            ->where('reports.type', 'Visit')
            ->select(
                'reports.*',
                'users.name',
                DB::raw("CONCAT(s.brand, ' ', unit.sku) AS brand_type")
            )
            ->groupBy('reports.id', 'unit.id')
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/client/general-history/{id}', function ($id) {
        $data = Reports::join('pic', 'pic.id', '=', 'reports.id_pic')
            ->join('users', 'users.id', '=', 'reports.id_technician')
            ->join('machine', 'machine.id', '=', 'reports.id_machine')
            ->join('unit', 'unit.id', '=', 'machine.id_unit')
            ->join('serial_product as s', 'unit.id', '=', 's.id_product')
            ->where('pic.id_client', $id)
            ->where('reports.type', 'General')
            ->select(
                'reports.*',
                'users.name',
                DB::raw("CONCAT(s.brand, ' ', unit.sku) AS brand_type")
            )
            ->groupBy('reports.id', 'unit.id')
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/productIn-supplier/{id}', function ($id) {
        $data = ProductIn::leftJoin('detail_product_in AS d', 'd.id_product_in', '=', 'product_in.id')
            ->leftJoin('detail_product AS dp', 'd.id_detail_product', '=', 'dp.id')
            ->leftJoin('product AS pr', 'dp.id_product', '=', 'pr.id')
            ->where('product_in.id_supplier', $id)
            ->whereNotNull('product_in.invoice')
            ->select(
                'product_in.*',
                DB::raw("CONCAT(pr.commodity, ' - ', dp.replacement) AS product"),
                DB::raw("CONCAT(d.qty, ' ', pr.unit) AS qty")
            )
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
    Route::get('/db/supplier', function () {
        $supplier = Supplier::all();
        return response()->json(['data' => $supplier]);
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
            ->select(
                's.*',
                DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND u.id = ' . Auth::user()->id . ') AS total'),
                DB::raw('(SELECT COALESCE(SUM(q.nett), 0) FROM quotation AS q 
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND u.id = ' . Auth::user()->id . ') AS price'),
                DB::raw('(SELECT COALESCE(COUNT(q.id), 0) FROM quotation AS q 
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
        AND q.level = "1"
        AND q.is_primary = "1"
        AND u.id = ' . Auth::user()->id . ') AS quote'),
                DB::raw(' CASE 
                WHEN s.semester = "1" THEN 1 
                WHEN s.semester = "2" THEN 7 
            END AS firstMonth
        '),
                DB::raw(' CASE 
                WHEN s.semester = "1" THEN 6 
                WHEN s.semester = "2" THEN 12 
            END AS lastMonth
        ')
            )
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

    Route::get('/db/prospect/support', function () {
        $prospect = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->leftJoin('users', 'users.id', '=', 'prospect.id_sales')
            ->leftJoin('quotation', 'quotation.id', '=', 'prospect.id_quotation')
            ->get(['prospect.id', 'prospect.category', 'prospect.kebutuhan', 'prospect.provide', 'prospect.date', 'client.company', 'users.name', 'users.image', 'pic.name_pic', 'quotation.status', 'quotation.nett']);
        return response()->json(['data' => $prospect]);
    });
    Route::get('/db/prospect/sales', function () {
        $prospect = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->leftJoin('users as sale', 'sale.id', '=', 'prospect.id_sales')
            ->leftJoin('users as supp', 'supp.id', '=', 'prospect.id_support')
            ->leftJoin('quotation', 'quotation.id', '=', 'prospect.id_quotation')
            ->where('sale.id', Auth::id())
            ->whereNull('prospect.level')
            ->get(['prospect.id', 'prospect.kebutuhan', 'prospect.date', 'client.company', 'supp.name', 'pic.name_pic', 'quotation.status', 'quotation.nett']);
        return response()->json(['data' => $prospect]);
    });
    Route::get('/db/prospect/sales/fu', function () {
        $prospect = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->leftJoin('users as sale', 'sale.id', '=', 'prospect.id_sales')
            ->leftJoin('users as supp', 'supp.id', '=', 'prospect.id_support')
            ->leftJoin('quotation', 'quotation.id', '=', 'prospect.id_quotation')
            ->where('sale.id', Auth::id())
            ->where('prospect.level', '9')
            ->get(['prospect.id', 'prospect.kebutuhan', 'prospect.date', 'client.company', 'supp.name', 'pic.name_pic', 'quotation.status', 'quotation.nett']);
        return response()->json(['data' => $prospect]);
    });
    Route::get('/db/prospect/admin', function () {
        $prospect = Prospect::join('pic', 'pic.id', '=', 'prospect.id_pic')
            ->join('client', 'client.id', '=', 'pic.id_client')
            ->leftJoin('users as sale', 'sale.id', '=', 'prospect.id_sales')
            ->leftJoin('users as supp', 'supp.id', '=', 'prospect.id_support')
            ->leftJoin('quotation', 'quotation.id', '=', 'prospect.id_quotation')
            ->where(function ($query) {
                $query->where('prospect.provide', '!=', '0')
                    ->orWhereNull('prospect.provide');
            })
            ->where(function ($query) {
                $query->where('prospect.level', '1')
                    ->orWhereNull('prospect.level');
            })
            ->get(['prospect.id', 'prospect.category', 'prospect.kebutuhan', 'prospect.provide', 'prospect.date', 'client.company', 'supp.name as support', 'sale.name as sales', 'pic.name_pic', 'sale.image', 'quotation.status', 'quotation.nett']);
        return response()->json(['data' => $prospect]);
    });


    Route::get('/db/library/marktool', function () {
        $library = Library::where('type', 'Marketing Tools')->get();
        return response()->json(['data' => $library]);
    });
    Route::get('/db/library/brosur', function () {
        $library = Library::where('type', 'Brosur')->get();
        return response()->json(['data' => $library]);
    });
    Route::get('/db/library/partlist', function () {
        $library = Library::where('type', 'Partlist')->get();
        return response()->json(['data' => $library]);
    });
    Route::get('/db/library/manbook', function () {
        $library = Library::where('type', 'Manual Book')->get();
        return response()->json(['data' => $library]);
    });
    Route::get('/db/library/manbook', function () {
        $library = Library::where('type', 'Manual Book')->get();
        return response()->json(['data' => $library]);
    });
    Route::get('/db/notulen/mention', function () {
        $notulen = Notulen::join('mention_notulen as m', 'm.id_notulen', '=', 'notulen.id')->join('users as u', 'm.id_mention', '=', 'u.id')->where('id_notuler', Auth::id())->get(['notulen.*', 'u.name', 'm.level']);
        return response()->json(['data' => $notulen]);
    });
    Route::get('/db/notulen/mention/admin', function () {
        $notulen = Notulen::join('mention_notulen as m', 'm.id_notulen', '=', 'notulen.id')->join('users as u', 'm.id_mention', '=', 'u.id')->get(['notulen.*', 'u.name', 'm.level', 'm.id as mId']);
        return response()->json(['data' => $notulen]);
    });
    Route::get('/db/notulen', function () {
        $notulen = Notulen::join('mention_notulen as m', 'm.id_notulen', '=', 'notulen.id')->join('users as u', 'm.id_mention', '=', 'u.id')->where('m.id_mention', Auth::id())->get(['notulen.*', 'u.name', 'm.level']);
        return response()->json(['data' => $notulen]);
    });
    Route::get('/db/machine/monitoring/{id}', function ($id) {
        $data = Monitoring::join('machine as m', 'm.id', '=', 'monitoring.id_machine')
            ->join('users', 'users.id', '=', 'monitoring.id_pic')
            ->where('m.id', $id)
            ->select(
                'monitoring.*',
                'users.name',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/machine/monitoring-weekly/{id}', function ($id) {
        $data = MonitoringWeekly::join('machine as m', 'm.id', '=', 'monitoring_weekly.id_machine')
            ->join('users', 'users.id', '=', 'monitoring_weekly.id_pic')
            ->where('m.id', $id)
            ->select(
                'monitoring_weekly.*',
                'users.name',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/machine/client/{id}', function ($id) {
        $data = Machine::join('client as c', 'c.id', '=', 'machine.id_client')
            ->leftJoin('serial_product as s', 's.id', '=', 'machine.id_unit')
            ->leftJoin('unit as u', 's.id_product', '=', 'u.id')
            ->where('c.id', $id)
            ->groupBy('machine.id', 'u.id')
            ->select(
                'machine.*',
                's.bar',
                'u.sku',
                'u.unit',
                's.brand',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/detail-weekly/fp', function () {
        $month = now()->month;
        $year = now()->year;

        $machines = Machine::join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as un', 'un.id', '=', 'sp.id_product')
            ->whereNotBetween('machine.id', [472, 481])
            ->where('machine.id_client', 1277)
            ->select(
                'machine.id',
                'machine.tag',
                'machine.location',
                DB::raw("CONCAT(sp.brand, ' ', un.sku) as machine")
            )
            ->get();

        $results = [];

        foreach ($machines as $machine) {
            $weeks = MonitoringWeekly::where('id_machine', $machine->id)
                ->whereMonth('date', operator: $month)
                ->whereYear('date', $year)
                ->pluck('week')
                ->toArray();

            $cleaning = Reports::where('id_machine', $machine->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('type', 'Cleaning')
                ->count();

            $results[] = [
                'id' => $machine->id,
                'machine' => $machine->machine,
                'tag' => $machine->tag,
                'location' => $machine->location,
                'month' => $month,
                'cleaning' => $cleaning,
                'week1' => in_array(1, $weeks) ? 1 : 0,
                'week2' => in_array(2, $weeks) ? 1 : 0,
                'week3' => in_array(3, $weeks) ? 1 : 0,
                'week4' => in_array(4, $weeks) ? 1 : 0,
                'week5' => in_array(5, $weeks) ? 1 : 0,
            ];
        }
        return response()->json(['data' => $results]);
    });
    Route::get('/db/compressor/fp', function () {
        $today = Carbon::today();
        $data = Machine::leftJoin('monitoring as m', function ($join) use ($today) {
            $join->on('machine.id', '=', 'm.id_machine')
                ->whereDate('m.date', '=', $today); // Menyaring berdasarkan tanggal monitoring
        })
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic')
            ->where('machine.id_client', 1277)
            ->whereNotBetween('machine.id', [472, 481])
            ->select(
                'machine.*',
                DB::raw("CONCAT(sp.brand, ' ', u.sku) as brand_type"),
                'm.condition',
                'u.unit',
                'm.created_at as date',
                'us.name'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/dryer/fp', function () {
        $data = Machine::join('client as c', 'c.id', '=', 'machine.id_client')
            ->join('serial_product as s', 's.id', '=', 'machine.id_unit')
            ->join('unit as u', 's.id_product', '=', 'u.id')
            ->where('u.unit', 'REFRIGERANT AIR DRYER')
            ->where('c.id', 1277)
            ->groupBy('machine.id', 'u.id')
            ->select(
                'machine.*',
                's.bar',
                'u.sn',
                'u.sku',
                'u.unit',
                's.brand',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pic/{id}', function ($id) {
        $data = PIC::where('id_client', $id)->get();
        return response()->json(['data' => $data]);
    });

    Route::get('/db/pending/po/non-project', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->whereNot('pending_po.status', [6, 7])
            ->where('q.id_sales', Auth::user()->id)
            ->groupBy('q.id')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%y') as po_date"),
                'q.title',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/po/done', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->where('pending_po.status', 6)
            ->where('q.id_sales', Auth::user()->id)
            ->select(
                'pending_po.id',
                'u.name',
                'c.company',
                'i.no_po',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/po/non-project/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->whereNot('pending_po.status', [6, 7])
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.title',
                'pending_po.no_pending',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/po/project/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->whereNot('pending_po.status', [6, 7])
            ->where('pending_po.type', 'Project')
            ->groupBy('q.id')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%y') as po_date"),
                'q.title',
                'c.company',
                'i.no_po',
                'u.name',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/po/done/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->where('pending_po.status', 6)
            ->select(
                'pending_po.id',
                'u.name',
                'c.company',
                'i.no_po',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/po/done/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->where('pending_po.status', 8)
            ->select(
                'pending_po.id',
                'u.name',
                'c.company',
                'i.no_po',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/new-order/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 0)
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%Y') as po_date"),
                'c.company',
                'c.area',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-list/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->whereIn('pending_po.status', [1, 2, 3, 4])
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%Y') as po_date"),
                'c.company',
                'c.area',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-ready', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 2)
            ->where('pending_po.type', 'Non Project')
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%Y') as po_date"),
                'c.company',
                'c.area',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-jadwal', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->leftJoin(DB::raw("(SELECT so1.* 
                            FROM service_order so1 
                            INNER JOIN (
                                SELECT id_sales_order, MAX(id) as max_id 
                                FROM service_order 
                                GROUP BY id_sales_order
                            ) so2 ON so1.id = so2.max_id
                        ) as so"), 'so.id_sales_order', '=', 'pending_po.id')
            ->where('pending_po.status', 2)
            ->where('pending_po.type', 'Project')
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'so.date_schedule',
                'so.note_schedule',
                'so.id as id_order',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%Y') as po_date"),
                'c.company',
                'c.area',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-delivery/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 5)
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-retur', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 8)
            ->where('pending_po.type', 'Non Project')
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-completed-non/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 6)
            ->where('pending_po.type', 'Non Project')
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-delay', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 9)
            // ->where('pending_po.type', 'Non Project')
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-completed-project/admin', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 6)
            ->where('pending_po.type', 'Project')
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                DB::raw("SUBSTRING(i.no_invoice, 1, 12) as short_invoice"),
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/new-order', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 0)
            ->where('q.id_sales', Auth::user()->id)
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%Y') as po_date"),
                'c.company',
                'c.area',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-list', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->whereIn('pending_po.status', [1, 2, 3, 4])
            ->where('q.id_sales', Auth::user()->id)
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                DB::raw("q.po_date as po_date_raw"),
                DB::raw("DATE_FORMAT(q.po_date, '%d-%m-%Y') as po_date"),
                'c.company',
                'c.area',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-delivery', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 5)
            ->where('q.id_sales', Auth::user()->id)
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-completed-non', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 6)
            ->where('pending_po.type', 'Non Project')
            ->where('q.id_sales', Auth::user()->id)
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/pending/sales-completed-project', function () {
        $data = PendingPO::join('quotation as q', 'pending_po.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'q.id', '=', 'i.id_quotation')
            ->join('pic as p', 'q.id_pic', '=', 'p.id')
            ->join('client as c', 'p.id_client', '=', 'c.id')
            ->join('users as u', 'q.id_sales', '=', 'u.id')
            ->leftJoin(DB::raw("(SELECT p1.* 
                                        FROM payment p1 
                                        INNER JOIN (
                                            SELECT id_quotation, MAX(id) as max_id 
                                            FROM payment 
                                            GROUP BY id_quotation
                                        ) p2 ON p1.id = p2.max_id
                                        ) as pay"), 'q.id', '=', 'pay.id_quotation')
            ->where('pending_po.status', 6)
            ->where('pending_po.type', 'Project')
            ->where('q.id_sales', Auth::user()->id)
            ->groupBy('q.id')
            ->orderBy('q.po_date', 'desc')
            ->select(
                'pending_po.id',
                'pending_po.delivery',
                'pending_po.no_pending',
                'pending_po.type',
                'pending_po.title',
                'u.name',
                'q.po_date',
                'c.company',
                'i.no_po',
                'u.name',
                'q.id_sales as team',
                'pending_po.status',
                'i.status_p',
                'i.note_p',
                'pay.type as paytype',
                'pay.level'
            )
            ->get();
        return response()->json(['data' => $data]);
    });

    Route::get('/db/sale-report/product/{year}', function ($year) {
        $startDate1 = Carbon::createFromDate($year, 1)->startOfMonth()->toDateString();
        $endDate1 = Carbon::createFromDate($year, 6)->endOfMonth()->toDateString();

        $startDate2 = Carbon::createFromDate($year, 7)->startOfMonth()->toDateString();
        $endDate2 = Carbon::createFromDate($year, 12)->endOfMonth()->toDateString();

        $pOutSemester1 = DB::table('product as p')
            ->join('detail_product as dp', 'dp.id_product', '=', 'p.id')
            ->join('detail_product_out as dpo', 'dpo.id_detail_product', '=', 'dp.id')
            ->join('product_out as po', 'dpo.id_product_out', '=', 'po.id')
            ->whereIn('p.category', ['Consumable Part', 'Non Consumable Part'])
            ->whereBetween('po.date', [$startDate1, $endDate1])
            ->select('p.id', 'p.commodity', DB::raw('SUM(dpo.qty) as total_keluar'))
            ->groupBy('p.id', 'p.commodity')
            ->get();

        $pOutSemester2 = DB::table('product as p')
            ->join('detail_product as dp', 'dp.id_product', '=', 'p.id')
            ->join('detail_product_out as dpo', 'dpo.id_detail_product', '=', 'dp.id')
            ->join('product_out as po', 'dpo.id_product_out', '=', 'po.id')
            ->whereIn('p.category', ['Consumable Part', 'Non Consumable Part'])
            ->whereBetween('po.date', [$startDate2, $endDate2])
            ->select('p.id', 'p.commodity', DB::raw('SUM(dpo.qty) as total_keluar'))
            ->groupBy('p.id', 'p.commodity')
            ->get();

        $pInSemester1 = DB::table('product as p')
            ->join('detail_product as dp', 'dp.id_product', '=', 'p.id')
            ->join('detail_product_in as dpi', 'dpi.id_detail_product', '=', 'dp.id')
            ->join('product_in as pi', 'dpi.id_product_in', '=', 'pi.id')
            ->whereIn('p.category', ['Consumable Part', 'Non Consumable Part'])
            ->whereBetween('pi.date', [$startDate1, $endDate1])
            ->select('p.id', 'p.commodity', DB::raw('SUM(dpi.qty) as total_keluar'))
            ->groupBy('p.id', 'p.commodity')
            ->get();

        $pInSemester2 = DB::table('product as p')
            ->join('detail_product as dp', 'dp.id_product', '=', 'p.id')
            ->join('detail_product_in as dpi', 'dpi.id_detail_product', '=', 'dp.id')
            ->join('product_in as pi', 'dpi.id_product_in', '=', 'pi.id')
            ->whereIn('p.category', ['Consumable Part', 'Non Consumable Part'])
            ->whereBetween('pi.date', [$startDate2, $endDate2])
            ->select('p.id', 'p.commodity', DB::raw('SUM(dpi.qty) as total_keluar'))
            ->groupBy('p.id', 'p.commodity')
            ->get();
        $products = Product::whereIn('category', ['Consumable Part', 'Non Consumable Part'])->get();
        $pIn1 = $pInSemester1->keyBy('id');
        $pOut1 = $pOutSemester1->keyBy('id');
        $pIn2 = $pInSemester2->keyBy('id');
        $pOut2 = $pOutSemester2->keyBy('id');

        $result = [];

        foreach ($products as $product) {
            $id = $product->id;

            $in1 = $pIn1[$id]->total_keluar ?? 0;
            $out1 = $pOut1[$id]->total_keluar ?? 0;
            $in2 = $pIn2[$id]->total_keluar ?? 0;
            $out2 = $pOut2[$id]->total_keluar ?? 0;

            $result[] = [
                'id' => $product->id,
                'commodity' => $product->commodity,
                'GO' => $product->go,
                'pIn1' => $in1,
                'pOut1' => $out1,
                'pIn2' => $in2,
                'pOut2' => $out2,
                'AllStock' => $product->stock + $product->warehouse_stock,
            ];
        }
        return response()->json(['data' => $result]);
    });
    Route::get('/db/machine/prokemas', function () {

        $today = Carbon::now();
        $month = Carbon::now()->month;

        $machines = Machine::leftJoin('monitoring as m', function ($join) use ($today) {
            $join->on('machine.id', '=', 'm.id_machine')
                ->whereDate('m.date', '=', $today);
        })
            ->leftJoin('users as us', 'us.id', '=', 'm.id_pic') // pakai leftJoin biar nggak hilang
            ->join('serial_product as sp', 'sp.id', '=', 'machine.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->whereIn('machine.id', [495, 496])
            ->groupBy('machine.id')
            ->addSelect(
                'machine.id',
                'us.name',
                DB::raw("DATE_FORMAT(m.created_at, '%H:%i') as time"),
                'sp.brand',
                'u.sku',
                'sp.pn',
                'u.unit',
                'machine.serial',
                DB::raw($month . ' as month')
            )
            ->get();

        return response()->json(['data' => $machines]);
    });
    Route::get('/db/issue/prokemas', function () {

        $today = Carbon::now();
        $month = Carbon::now()->month;

        $issue = Monitoring::join('machine as mc', 'mc.id', '=', 'monitoring.id_machine')
            ->leftJoin('users as us', 'us.id', '=', 'monitoring.id_pic')
            ->join('serial_product as sp', 'sp.id', '=', 'mc.id_unit')
            ->join('unit as u', 'u.id', '=', 'sp.id_product')
            ->whereIn('mc.id', [495, 496])
            ->whereNotNull('monitoring.issue')
            ->where('monitoring.issue_level', 0)
            ->whereDate('monitoring.date', $today)
            ->select(
                'monitoring.*',
                'us.name',
                'sp.brand',
                'u.sku',
                'sp.pn',
                'u.unit',
                'mc.serial'
            )
            ->get();

        return response()->json(['data' => $issue]);
    });
    Route::get('/db/change-warehouse/recieve', function () {
        $to = Auth::user()->id == 23 ? 'BKS' : 'BDG';

        $warehouse = ChangeWarehouse::join('users as s', 'change_warehouse.id_sender', '=', 's.id')
            ->leftJoin('users as r', 'change_warehouse.id_reciever', '=', 'r.id')
            ->when(Auth::user()->role != 'Admin', function ($query) use ($to) {
                $query->where('change_warehouse.to', $to);
            })
            ->where('change_warehouse.status', '!=', 2)
            ->orderBy('change_warehouse.status')
            ->orderBy('change_warehouse.date', 'desc')
            ->select(
                'change_warehouse.*',
                'r.name as receiver',
                's.name as sender',
                DB::raw("DATE_FORMAT(change_warehouse.date, '%d-%m-%Y') as date_dmy")
            )
            ->get();

        return response()->json(['data' => $warehouse]);
    });
    Route::get('/db/change-warehouse/done', function () {
        $warehouse = ChangeWarehouse::join('users as s', 'change_warehouse.id_sender', '=', 's.id')
            ->join('users as r', 'change_warehouse.id_reciever', '=', 'r.id')
            ->where('change_warehouse.status', '=', 2)
            ->orderBy('change_warehouse.status')
            ->orderBy('change_warehouse.date', 'desc')
            ->select(
                'change_warehouse.*',
                'r.name as receiver',
                's.name as sender',
                DB::raw("DATE_FORMAT(change_warehouse.date, '%d-%m-%Y') as date_dmy"),
                DB::raw("DATE_FORMAT(change_warehouse.date_recieve, '%d-%m-%Y') as date_recieve_dmy")
            )
            ->get();

        return response()->json(['data' => $warehouse]);
    });

    Route::get('/db/account/data', function () {
        $account = Account::orderByDesc('id')->get();
        return response()->json(['data' => $account]);
    });
    Route::get('/db/expense/data', function () {
        $expense = Expense::whereNotNULL('id_bank')->get();
        return response()->json(['data' => $expense]);
    });
    Route::get('/db/expense/umum/data', function () {
        $expense = Expense::whereNULL('id_bank')->get();
        return response()->json(['data' => $expense]);
    });
    Route::get('/db/purchase-request/new', function () {
        $purchase = PurchaseRequest::join('pending_po as p', 'purchase_request.id_pending', '=', 'p.id')
            ->join('serial_product as s', 'purchase_request.id_equivalent', '=', 's.id')
            ->join('product as pr', 'pr.id', '=', 's.id_product')
            ->join('quotation as q', 'p.id_quotation', '=', 'q.id')
            ->leftJoin('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as pi', 'pi.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'pi.id_client')
            ->where('purchase_request.status', '0')
            ->select(
                'p.id',
                'purchase_request.date',
                'p.no_pending',
                'i.no_po',
                'c.company',
                'purchase_request.note',
                DB::raw("CONCAT(purchase_request.qty, ' ', pr.unit) as qty_full"),
                DB::raw("CONCAT(s.brand, ' ', s.pn, ' (', SUBSTRING(pr.go, 1, 1) , ')') as item")
            )->get();
        return response()->json(['data' => $purchase]);
    });
    Route::get('/db/purchase-request/acc', function () {
        $purchase = PurchaseRequest::join('pending_po as p', 'purchase_request.id_pending', '=', 'p.id')
            ->join('serial_product as s', 'purchase_request.id_equivalent', '=', 's.id')
            ->join('product as pr', 'pr.id', '=', 's.id_product')
            ->join('quotation as q', 'p.id_quotation', '=', 'q.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as pi', 'pi.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'pi.id_client')
            ->where('purchase_request.status', '1')
            ->select(
                'p.id',
                'purchase_request.date',
                'p.no_pending',
                'i.no_po',
                'c.company',
                'purchase_request.note',
                DB::raw("CONCAT(purchase_request.qty, ' ', pr.unit) as qty_full"),
                DB::raw("CONCAT(s.brand, ' ', s.pn, ' (', SUBSTRING(pr.go, 1, 1) , ')') as item")
            )->get();
        return response()->json(['data' => $purchase]);
    });
    Route::get('/db/purchase-request/delivery', function () {
        $purchase = PurchaseRequest::join('pending_po as p', 'purchase_request.id_pending', '=', 'p.id')
            ->join('serial_product as s', 'purchase_request.id_equivalent', '=', 's.id')
            ->join('product as pr', 'pr.id', '=', 's.id_product')
            ->join('quotation as q', 'p.id_quotation', '=', 'q.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as pi', 'pi.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'pi.id_client')
            ->where('purchase_request.status', '2')
            ->select(
                'p.id',
                'purchase_request.date',
                'p.no_pending',
                'i.no_po',
                'c.company',
                'purchase_request.note',
                DB::raw("CONCAT(purchase_request.qty, ' ', pr.unit) as qty_full"),
                DB::raw("CONCAT(s.brand, ' ', s.pn, ' (', SUBSTRING(pr.go, 1, 1) , ')') as item")
            )->get();
        return response()->json(['data' => $purchase]);
    });
    Route::get('/db/purchase-request/done', function () {
        $purchase = PurchaseRequest::join('pending_po as p', 'purchase_request.id_pending', '=', 'p.id')
            ->join('serial_product as s', 'purchase_request.id_equivalent', '=', 's.id')
            ->join('product as pr', 'pr.id', '=', 's.id_product')
            ->join('quotation as q', 'p.id_quotation', '=', 'q.id')
            ->join('invoice as i', 'i.id_quotation', '=', 'q.id')
            ->join('pic as pi', 'pi.id', '=', 'q.id_pic')
            ->join('client as c', 'c.id', '=', 'pi.id_client')
            ->where('purchase_request.status', '3')
            ->select(
                'p.id',
                'purchase_request.date',
                'p.no_pending',
                'i.no_po',
                'c.company',
                'purchase_request.note',
                DB::raw("CONCAT(purchase_request.qty, ' ', pr.unit) as qty_full"),
                DB::raw("CONCAT(s.brand, ' ', s.pn, ' (', SUBSTRING(pr.go, 1, 1) , ')') as item")
            )->get();
        return response()->json(['data' => $purchase]);
    });
    Route::get('/db/payable/invoice', function () {
        $payable = ProductIn::leftJoin('supplier as s', 'product_in.id_supplier', '=', 's.id')
            ->leftJoin('detail_product_in as d', 'product_in.id', '=', 'd.id_product_in')
            ->select(
                'product_in.id',
                'product_in.invoice',
                'product_in.accept',
                'product_in.total',
                'product_in.supplier as d_supplier',
                's.supplier',
                DB::raw('SUM(d.qty) as total_qty'),
                DB::raw("DATE_FORMAT(product_in.date, '%d-%m-%Y') as tanggal")
            )
            ->groupBy(
                'product_in.id',
                'product_in.invoice'
            )
            ->orderByDesc('product_in.date')
            ->whereNotNull('product_in.invoice')
            ->get();

        return response()->json(['data' => $payable]);
    });
    Route::get('/db/payable/aging', function () {
        $payable = ProductIn::leftJoin('supplier as s', 'product_in.id_supplier', '=', 's.id')
            ->leftJoin('detail_product_in as d', 'product_in.id', '=', 'd.id_product_in')
            ->select(
                'product_in.id',
                'product_in.invoice',
                'product_in.accept',
                'product_in.total',
                'product_in.supplier as d_supplier',
                's.supplier',
                DB::raw('SUM(d.qty) as total_qty'),
                DB::raw("DATE_FORMAT(product_in.date, '%d-%m-%Y') as tanggal"),
                DB::raw("
                CASE
                    WHEN DATEDIFF(CURDATE(), product_in.date) > 0
                    THEN DATEDIFF(CURDATE(), product_in.date)
                    ELSE 0
                END as overdue
            ")
            )
            ->groupBy(
                'product_in.id',
                'product_in.invoice'
            )
            ->orderByDesc('product_in.date')
            ->where('accept', '0')
            ->get();

        return response()->json(['data' => $payable]);
    });
    Route::get('/db/payable/receipt', function () {
        $payable = ProductIn::leftJoin('supplier as s', 'product_in.id_supplier', '=', 's.id')
            ->leftJoin('detail_product_in as d', 'product_in.id', '=', 'd.id_product_in')
            ->select(
                'product_in.id',
                'product_in.invoice',
                'product_in.accept',
                'product_in.total',
                'product_in.info',
                'product_in.supplier as d_supplier',
                's.supplier',
                DB::raw('SUM(d.qty) as total_qty'),
                DB::raw("DATE_FORMAT(product_in.date, '%d-%m-%Y') as tanggal"),
                DB::raw("
                    CONCAT(
                        '#PAY-',
                        LPAD(
                            (
                                SELECT COUNT(*)
                                FROM product_in pi2
                                WHERE YEAR(pi2.date) = YEAR(product_in.date)
                                AND pi2.id <= product_in.id
                            ),
                            3,
                            '0'
                        ),
                        '-',
                        RIGHT(YEAR(product_in.date), 2)
                    ) as no_receipt
                ")
            )
            ->groupBy(
                'product_in.id',
                'product_in.invoice',
                'product_in.accept',
                'product_in.total',
                'product_in.info',
                'product_in.supplier',
                's.supplier',
                'product_in.date'
            )
            ->whereNotNULL('invoice')
            ->orderByDesc('product_in.date')
            ->get();

        return response()->json(['data' => $payable]);
    });
    Route::get('/db/stock/opname', function () {
        $data = StockOpname::join('users as u', 'u.id', '=', 'stock_opname.id_user')
            ->select(
                'stock_opname.*',
                'u.name',
                DB::raw("DATE_FORMAT(stock_opname.date, '%d-%m-%Y') as tanggal"),
                DB::raw("
                CONCAT(
                    'Periode Quarter ',
                    CASE stock_opname.periode
                        WHEN 1 THEN 'I'
                        WHEN 2 THEN 'II'
                        WHEN 3 THEN 'III'
                        WHEN 4 THEN 'IV'
                        ELSE '-'
                    END
                ) as periode_label
            ")
            )
            ->get();

        return response()->json(['data' => $data]);
    });
    Route::get('/db/stock/opname/{id}', function ($id) {

        $prevStockOpnameId = StockOpname::where('id', '<', $id)
            ->orderBy('id', 'desc')
            ->value('id');

        $data = DetailStockOpname::leftJoin('detail_product as dp', 'dp.id', '=', 'detail_stock_opname.id_product')
            ->leftJoin('product as p', 'p.id', '=', 'dp.id_product')
            ->leftJoin('detail_stock_opname as prev', function ($join) use ($prevStockOpnameId) {
                $join->on('prev.id_product', '=', 'detail_stock_opname.id_product')
                    ->where('prev.id_stock_opname', '=', $prevStockOpnameId);
            })
            ->where('detail_stock_opname.id_stock_opname', $id)
            ->select(
                'detail_stock_opname.*',
                DB::raw("
                    CONCAT(
                        dp.replacement,
                        ' (',
                        LEFT(COALESCE(p.go, 'N'), 1),
                        ')'
                    ) as replacement
                "),
                DB::raw('COALESCE(prev.stock_sistem, 0) as prev_qty')
            )
            ->get();

        return response()->json([
            'current_stock_opname_id' => $id,
            'previous_stock_opname_id' => $prevStockOpnameId,
            'data' => $data
        ]);
    });
    Route::get('/db/income/statment', function () {
        $data = LabaRugi::select(
            'laba_rugi.*',
            DB::raw("DATE_FORMAT(laba_rugi.date, '%d-%m-%Y') as tanggal")
        )->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/fixed-asset', function () {
        $data = FixedAsset::select(
            'fixed_asset.*',
            DB::raw("DATE_FORMAT(fixed_asset.beli, '%d-%m-%Y') as tanggal_beli"),
            DB::raw("DATE_FORMAT(fixed_asset.pakai, '%d-%m-%Y') as tanggal_pakai")
        )->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/purchase-order', function () {
        $data = PurchaseOrder::select(
            'purchase_order.*',
            DB::raw("DATE_FORMAT(purchase_order.date, '%d-%m-%Y') as tanggal")
        )->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/product/set', function () {
        $data = ProductSet::join('product as p', 'p.id', '=', 'product_set.id_product')->groupBy('product_set.id')->select('product_set.*', 'p.description', 'p.commodity', 'p.stock', 'p.unit')->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/bangkrupt', function () {
        $data = Client::whereHas('crmStatus', function ($q) {
            $q->where('status', 1);
        })
            ->orWhereDoesntHave('crmStatus')
            ->get();
        return response()->json(['data' => $data]);
    });
    Route::get('/db/bangkrupt/sales', function () {
        $data = Client::whereHas('crmStatus', function ($q) {
            $q->where('status', 1);
        })
            ->orWhereDoesntHave('crmStatus')
            ->where('id_sales', Auth::user()->id)
            ->get();
        return response()->json(['data' => $data]);
    });
});
Auth::routes();