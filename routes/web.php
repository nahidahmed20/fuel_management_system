<?php

use App\Models\BorrowMoney;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FuelController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\NozzleController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BorrowerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FuelSellController;
use App\Http\Controllers\FuelTypeController;
use App\Http\Controllers\MobilOutController;
use App\Http\Controllers\FuelShortController;
use App\Http\Controllers\FuelStockController;
use App\Http\Controllers\MobilStockController;
use App\Http\Controllers\BorrowMoneyController;
use App\Http\Controllers\CustomerDueController;
use App\Http\Controllers\LoanPaymentController;
use App\Http\Controllers\NozzleMeterController;
use App\Http\Controllers\ProductSaleController;
use App\Http\Controllers\CashWithdrawController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\frontend\FrontendController;
use App\Http\Controllers\CustomerDuePaymentController;

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/', [FrontendController::class, 'homeDashboard'])->name('home.dashboard');
Route::get('/home', [HomeController::class, 'home'])->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard/data', [HomeController::class, 'getData'])->name('dashboard.data');

    Route::prefix('fuel/types')->group(function () {
        Route::get('/index', [FuelTypeController::class, 'index'])->name('fuel-type.index');
        Route::get('/create', [FuelTypeController::class, 'create'])->name('fuel-type.create');
        Route::post('/store', [FuelTypeController::class, 'store'])->name('fuel-type.store');
        Route::get('/edit/{id}', [FuelTypeController::class, 'edit'])->name('fuel-type.edit');
        Route::post('/update/{id}', [FuelTypeController::class, 'update'])->name('fuel-type.update');
        Route::get('/destory/{id}', [FuelTypeController::class, 'destroy'])->name('fuel-type.destroy');
    });

    Route::prefix('nozzle')->group(function () {
        Route::get('/index', [NozzleController::class, 'index'])->name('nozzle.index');
        Route::get('/create', [NozzleController::class, 'create'])->name('nozzle.create');
        Route::post('/store', [NozzleController::class, 'store'])->name('nozzle.store');
        Route::get('/{id}/edit', [NozzleController::class, 'edit'])->name('nozzle.edit');
        Route::post('/{id}/update', [NozzleController::class, 'update'])->name('nozzle.update');
        Route::get('/{id}/destroy', [NozzleController::class, 'destroy'])->name('nozzle.destroy');
        Route::get('/meters/index', [NozzleController::class, 'NozzleMeterList'])->name('nozzle.meter.index');
        Route::get('/meters/create', [NozzleController::class, 'nozzleMeterCreate'])->name('nozzle.meter.create');
        Route::post('/meters/store', [NozzleController::class, 'nozzleMeterStore'])->name('nozzle.meter.store');
        Route::get('/meters/edit/{id}', [NozzleController::class, 'nozzleMeterEdit'])->name('nozzle.meter.edit');
        Route::post('/meters/update/{id}', [NozzleController::class, 'nozzleMeterUpdate'])->name('nozzle.meter.update');
        Route::get('/meters/destroy/{id}', [NozzleController::class, 'nozzleMeterDestroy'])->name('nozzle.meter.destroy');
    });
     Route::get('/get-latest-meter/{nozzle_id}', [NozzleController::class, 'getLatestMeter'])->name('nozzle.getLatestMeter');

    Route::prefix('fuel/stock')->group(function () {
        Route::get('/index', [FuelStockController::class, 'index'])->name('fuel.stock.index');
        Route::get('/create', [FuelStockController::class, 'create'])->name('fuel.stock.create');
        Route::post('/store', [FuelStockController::class, 'store'])->name('fuel.stock.store');
        Route::get('/edit{id}', [FuelStockController::class, 'edit'])->name('fuel.stock.edit');
        Route::post('/update{id}', [FuelStockController::class, 'update'])->name('fuel.stock.update');
        Route::get('/delete{id}', [FuelStockController::class, 'destroy'])->name('fuel.stock.destroy');
    });

    Route::prefix('fuel/short')->group(function () {
        Route::get('/', [FuelShortController::class, 'index'])->name('fuel.short.index');
        Route::get('/create', [FuelShortController::class, 'create'])->name('fuel.short.create');
        Route::post('/store', [FuelShortController::class, 'store'])->name('fuel.short.store');
        Route::get('/edit{id}', [FuelShortController::class, 'edit'])->name('fuel.short.edit');
        Route::post('/update{id}', [FuelShortController::class, 'update'])->name('fuel.short.update');
        Route::get('/delete{id}', [FuelShortController::class, 'destroy'])->name('fuel.short.destroy');
    });

    Route::prefix('fuel/seals')->group(function () {
        Route::get('/index', [FuelSellController::class, 'index'])->name('fuel.sell.index');
        Route::get('/create', [FuelSellController::class, 'create'])->name('fuel.sell.create');
        Route::post('/store', [FuelSellController::class, 'store'])->name('fuel.sell.store');
        Route::get('/edit{id}', [FuelSellController::class, 'edit'])->name('fuel.sell.edit');
        Route::post('/update{id}', [FuelSellController::class, 'update'])->name('fuel.sell.update');
        Route::get('/destroy{id}', [FuelSellController::class, 'destroy'])->name('fuel.sell.destroy');


        Route::get('/report/{type}', [FuelController::class, 'reportView'])->name('fuel.report.view');
        Route::get('/report/download/{type}', [FuelController::class, 'downloadPdf'])->name('fuel.report.download');
    });
    Route::get('/get-latest-selling-price/{fuel_type_id}', [FuelSellController::class, 'getLatestSellingPrice']);
    Route::get('/get-nozzles/by-fueltype', [FuelSellController::class, 'getNozzlesByFuelType'])->name('get.nozzles.by.fueltype');
    Route::get('/get-nozzle-meter/{nozzle_id}', [FuelSellController::class, 'getNozzleMeter']);
    Route::get('/fuel-sell/summary', [FuelSellController::class, 'summary'])->name('fuel.sell.summary');


    Route::prefix('mobil')->group(function () {
        Route::get('/stocks/index', [MobilStockController::class, 'index'])->name('mobil.index');
        Route::get('/stocks/create', [MobilStockController::class, 'create'])->name('mobil.create');
        Route::post('/stocks/store', [MobilStockController::class, 'storeStock'])->name('mobil.stock.store');
        Route::get('/stocks/{stock}/edit', [MobilStockController::class, 'editStock'])->name('mobil.stock.edit');
        Route::post('/stocks/{stock}', [MobilStockController::class, 'updateStock'])->name('mobil.stock.update');
        Route::get('/stocks/{stock}', [MobilStockController::class, 'deleteStock'])->name('mobil.stock.delete');

        Route::get('/seals/index', [MobilOutController::class, 'indexOut'])->name('mobilOut.index');
        Route::get('/seals/create', [MobilOutController::class, 'createOut'])->name('mobilOut.create');
        Route::post('/seals/store', [MobilOutController::class, 'storeOut'])->name('mobil.out.store');
        Route::get('/seals/edit{id}', [MobilOutController::class, 'editOut'])->name('mobilOut.edit');
        Route::post('/seals/update{id}', [MobilOutController::class, 'updateOut'])->name('mobilOut.update');
        Route::get('/seals/destroy{id}', [MobilOutController::class, 'destroyOut'])->name('mobilOut.destroy');

        Route::get('/report/view/{type}', [MobilController::class, 'reportView'])->name('mobil.report.view');
        Route::get('/report/download/{type}', [MobilController::class, 'download'])->name('mobil.report.download');
        Route::get('/report/data/{type}', [MobilController::class, 'ajaxData']);


    });

    // Nojel Meter Reading Route
    Route::prefix('nozzle-meters')->group(function () {
        Route::get('/create', [NozzleMeterController::class, 'create'])->name('nozzle-meters.create');
        Route::post('/store', [NozzleMeterController::class, 'store'])->name('nozzle-meters.store');
        Route::get('/edit/{id}', [NozzleMeterController::class, 'edit'])->name('nozzle-meters.edit');
        Route::post('/update/{id}', [NozzleMeterController::class, 'update'])->name('nozzle-meters.update');
        Route::get('/delete/{id}', [NozzleMeterController::class, 'destroy'])->name('nozzle-meters.destroy');
    });
    Route::get('/get-nozzles-by-fuel/{fuel_type_id}', [NozzleMeterController::class, 'getNozzlesByFuel']);
    Route::get('/get-prev-meter/{nozzle_id}', [NozzleMeterController::class, 'getPrevMeter']);
    Route::get('/get-nozzle-meter/{nozzle_id}', [NozzleMeterController::class, 'getNozzleMeter'])->name('get.nozzle.meter');

    // Product Route
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('product.index');
        Route::get('/create', [ProductController::class, 'createProduct'])->name('product.create');
        Route::post('/store', [ProductController::class, 'storeProduct'])->name('product.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::post('/{product}/update', [ProductController::class, 'update'])->name('product.update');
        Route::get('/{product}/destroy', [ProductController::class, 'destroy'])->name('product.destroy');
    });

    // Product Store Route
    Route::prefix('product/stocks')->group(function () {
        Route::get('/', [ProductStockController::class, 'index'])->name('product.stock.index');
        Route::get('/create', [ProductStockController::class, 'create'])->name('product.stock.create');
        Route::post('/store', [ProductStockController::class, 'store'])->name('product.stock.store');
        Route::get('/edit/{id}', [ProductStockController::class, 'edit'])->name('product.stock.edit');
        Route::post('/update/{id}', [ProductStockController::class, 'update'])->name('product.stock.update');
        Route::get('/destroy/{id}', [ProductStockController::class, 'destroy'])->name('product.stock.destroy');
    });

    // Product Sales Route
    Route::prefix('product/sales')->group(function () {
        Route::get('/', [ProductSaleController::class, 'index'])->name('product.sales.index');
        Route::get('/create', [ProductSaleController::class, 'create'])->name('product.sales.create');
        Route::post('/store', [ProductSaleController::class, 'store'])->name('product.sales.store');
        Route::get('/edit/{id}', [ProductSaleController::class, 'edit'])->name('product.sales.edit');
        Route::post('/update/{id}', [ProductSaleController::class, 'update'])->name('product.sales.update');
        Route::get('/destroy/{id}', [ProductSaleController::class, 'destroy'])->name('product.sales.destroy');
        Route::get('/summary', [ProductSaleController::class, 'productSummary'])->name('product.summary');

    });

    Route::get('/product/report/{type}', [ProductController::class, 'reportView'])->name('product.report.view');
    Route::get('/product/report-pdf/{type}', [ProductController::class, 'downloadProductPdf'])->name('product.report.download');

    // Expense Route
    Route::prefix('expense')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('expense.index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('expense.create');
        Route::post('/store', [ExpenseController::class, 'store'])->name('expense.store');
        Route::get('/edit/{id}', [ExpenseController::class, 'edit'])->name('expense.edit');
        Route::put('/update/{id}', [ExpenseController::class, 'update'])->name('expense.update');
        Route::get('/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
        Route::get('/all/report', [ExpenseController::class, 'expenseReport'])->name('expense.report');
        Route::get('/report/pdf/{type}', [ExpenseController::class, 'expenseReportPdf'])->name('expense.report.pdf');
    });

    Route::resource('customers', CustomerController::class);

    // Borrower Route
    Route::resource('borrowers', BorrowerController::class);
    Route::resource('loans', LoanController::class);

    Route::get('/loans/{borrower}', [BorrowerController::class, 'show'])->name('loans.show');
    Route::get('/borrower/due/payments/create/{id}', [BorrowerController::class, 'paymentForm'])->name('borrower.payments.create');

    Route::get('loan/report/today', [LoanController::class, 'downloadTodayPdf'])->name('loan.today');
    Route::get('loan/report/month', [LoanController::class, 'downloadMonthPdf'])->name('loan.month');
    Route::get('loan/report/year', [LoanController::class, 'downloadYearPdf'])->name('loan.year');

    Route::resource('loan-payments', LoanPaymentController::class);
    Route::get('/loan/payments/report/today', [LoanPaymentController::class, 'downloadTodayPdf'])->name('loan.payment.today');
    Route::get('/loan/payments/report/month', [LoanPaymentController::class, 'downloadMonthPdf'])->name('loan.payment.month');
    Route::get('/loan/payments/report/year', [LoanPaymentController::class, 'downloadYearPdf'])->name('loan.payment.year');
    Route::get('/loan/payment/total-due', [LoanPaymentController::class, 'getTotalBorrow'])->name('borrower_due.getTotalDue');

    Route::get('/loan/due/report/{type}', [LoanPaymentController::class, 'loanDueReportView'])->name('loan.due.report');
    Route::get('/loan/due/report/pdf/{type}', [LoanPaymentController::class, 'loanDueReportPdf'])->name('loan.due.report.pdf');
    Route::get('/loan/payment/report/{type}', [LoanPaymentController::class, 'loanPaymentReportView'])->name('loan.payment.report');
    Route::get('/loan/payment/report/pdf/{type}', [LoanPaymentController::class, 'loanPaymentReportPdf'])->name('loan.payment.report.pdf');

    // Customer Due Route
    Route::prefix('customer-due')->group(function () {
        Route::get('/list',[CustomerDueController::class,'index'])->name('customer_due.index');
        Route::get('/create',[CustomerDueController::class,'create'])->name('customer_due.create');
        Route::post('/store',[CustomerDueController::class,'store'])->name('customer_due.store');
        Route::get('/edit/{slug}', [CustomerDueController::class, 'edit'])->name('customer_due.edit');
        Route::post('/update',[CustomerDueController::class,'update'])->name('customer_due.update');
        Route::get('/delete/{id}', [CustomerDueController::class, 'destroy'])->name('customer_due.destroy');

    });
    Route::get('/customer_due/filter', [CustomerDueController::class, 'filterByDate'])->name('customer_due.filter');


    Route::resource('customer-due-payments', CustomerDuePaymentController::class);
    Route::get('/customer/due/total-due', [CustomerDueController::class, 'getTotalDue'])->name('customer_due.getTotalDue');
    Route::get('/customer/due/report/{type}', [CustomerDuePaymentController::class, 'customerDueReportView'])->name('customer.due.report');
    Route::get('/customer/due/report/pdf/{type}', [CustomerDuePaymentController::class, 'customerDueReportPdf'])->name('customer.due.report.pdf');
    Route::get('/customer/payment/report/{type}', [CustomerDuePaymentController::class, 'customerPaymentReportView'])->name('customer.payment.report');
    Route::get('/customer/payment/report/pdf/{type}', [CustomerDuePaymentController::class, 'customerPaymentReportPdf'])->name('customer.payment.report.pdf');

    // Account Route
    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account.index');
        Route::get('/create', [AccountController::class, 'create'])->name('account.create');
        Route::post('/store', [AccountController::class, 'store'])->name('account.store');
        Route::get('/edit/{id}', [AccountController::class, 'edit'])->name('account.edit');
        Route::put('/update/{id}', [AccountController::class, 'update'])->name('account.update');
        Route::get('/delete/{id}', [AccountController::class, 'destroy'])->name('account.destroy');
        Route::get('/all/report', [AccountController::class, 'accountReport'])->name('account.report');
        Route::get('/report/pdf/{type}', [AccountController::class, 'accountReportPdf'])->name('account.report.pdf');
    });

    // Account Route
    Route::prefix('cash/withdraw')->group(function () {
        Route::get('/', [CashWithdrawController ::class, 'index'])->name('cash.withdraw.index');
        Route::get('/create', [CashWithdrawController ::class, 'create'])->name('cash.withdraw.create');
        Route::post('/store', [CashWithdrawController ::class, 'store'])->name('cash.withdraw.store');
        Route::get('/edit/{id}', [CashWithdrawController ::class, 'edit'])->name('cash.withdraw.edit');
        Route::put('/update/{id}', [CashWithdrawController ::class, 'update'])->name('cash.withdraw.update');
        Route::get('/delete/{id}', [CashWithdrawController ::class, 'destroy'])->name('cash.withdraw.destroy');
        Route::get('/all/report', [CashWithdrawController ::class, 'cashWithdrawReport'])->name('cash.withdraw.report');
        Route::get('/report/pdf/{type}', [CashWithdrawController ::class, 'cashWithdrawReportPdf'])->name('cash.withdraw.report.pdf');
    });

    Route::get('/profit/all', [ProfitController::class, 'profitOverview'])->name('profit.all');
    Route::get('/profit/custom', [ProfitController::class, 'profitCustom'])->name('profit.custom');
    Route::get('/balance', [ProfitController::class, 'monthlyBalanceAndProfitOverview'])->name('balance');
    Route::get('/current/cash', [ProfitController::class, 'currentCash'])->name('current.cash');



    // User Route
    Route::get('/user/list',[UserController::class,'index'])->name('user.list');
    Route::get('/user/create',[UserController::class,'userCreate'])->name('user.create');
    Route::post('/user/store',[UserController::class,'userStore'])->name('user.store');
    Route::get('/user/edit/{slug}',[UserController::class,'userEdit'])->name('user.edit');
    Route::post('/user/update/{id}',[UserController::class,'userUpdate'])->name('user.update');
    Route::get('/user/delete/{id}',[UserController::class,'userDelete'])->name('user.delete');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'profileEdit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
});

require __DIR__.'/auth.php';
