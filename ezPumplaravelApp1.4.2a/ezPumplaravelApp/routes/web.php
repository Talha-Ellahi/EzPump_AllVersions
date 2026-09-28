<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AtgController;
use App\Http\Controllers\BypassController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\SummmaryReportController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pumpdata;
use App\Http\Controllers\UserManagementController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SettingsController; // Added
use App\Http\Controllers\TankController; // Added
use App\Http\Controllers\TankShiftController; // Added
use App\Http\Controllers;
require_once 'tank.php';

Auth::routes();

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'attemptLoginFunction']);

// Publicly accessible utility route (if needed, otherwise protect it)
Route::get('/dump-database', [Controllers\DatabaseController::class, 'dumpDatabase']);

// Password setting routes (Require auth but maybe not a specific role initially)
Route::middleware(['auth'])->group(function () {
    Route::get('/set-password', [SetPasswordController::class, 'showSetPasswordForm'])->name('password.set');
    Route::post('/set-password', [SetPasswordController::class, 'update'])->name('empty.password.update');
});

// Main authenticated routes with role checks and session timeout
Route::group(['middleware' => ['auth', 'check.null.password', 'session.timeout','install.mode']], function () {
    Route::get('/', [pumpdata::class, 'index'])->name('home'); // Assuming home is for cashier+
    Route::get('/dataframe', [pumpdata::class, 'fresh_old']);
    Route::get('/dataframe-new', [pumpdata::class, 'fresh']);
    Route::post('/dataframe', [pumpdata::class, 'updated']); // Assuming related to dataframe views
    // --- Role 10 (Cashier) & Above ---
    Route::group(['middleware' => ['role:10']], function () {
        Route::get('/check-customer-limit', [App\Http\Controllers\HomeController::class, 'customerLimit']);
        Route::get('/getCustomers',  [pumpdata::class, 'getCustomers'])->name('getCustomers');
        Route::get('/getVehicles', [pumpdata::class, 'getVehicles'])->name('getVehicles');
        Route::post('/card_details', [pumpdata::class, 'card_details'])->name('card_details');
        Route::get('/dispensers', [App\Http\Controllers\PumpStreamController::class, 'index']);
        Route::get('/tag_status', [App\Http\Controllers\PumpStreamController::class, 'tagStatus']);
        Route::get('/atg_config', [App\Http\Controllers\PumpStreamController::class, 'atgStatus']);
        // Product Sales Routes
        // Route::get('/api/products', [App\Http\Controllers\ProductSaleController::class, 'getProducts'])->name('api.products');
        // Route::get('/api/customers', [App\Http\Controllers\ProductSaleController::class, 'getCustomers'])->name('api.customers');
        // Route::post('/api/product-sale', [App\Http\Controllers\ProductSaleController::class, 'store'])->name('api.product-sale');

    });

    // --- Role 6 (Shift Lead) & Above ---
    Route::group(['middleware' => ['role:6']], function () {
//        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); // General dashboard
        Route::view('/dashboard-new', 'dashboard-new')->name('dashboard-new'); // New dashboard view

        Route::view('/shift', 'Shift')->name('Shift.view');
        Route::get('/nozzle', function () { return view('NozzleNew'); })->name('nozzle.view');
        Route::get('/nozzle-old', function () { return view('Nozzle'); })->name('nozzle.old.view');
        Route::get('/shift_logs', function () { return view('ShiftLogs'); })->name('shiftlogs.view');
        Route::get('/shift_print', [App\Http\Controllers\HomeController::class, 'PrintShiftStats'])->name('shift.print');
        Route::get('/tanks/shift_print', [App\Http\Controllers\TankShiftController::class, 'shiftPrint'])->name('tank.shift-print'); // Tank shift print
        Route::get('/shift_print_by_date', [App\Http\Controllers\TankShiftController::class, 'shiftPrintByDate'])->name('tank.shift-print-by-date'); // Tank shift print by date
        Route::get('/shift_date_select', function () { return view('reports.date_selection'); })->name('tank.shift-date-select');
        // Tank routes included via require_once('tank.php') should ideally have middleware applied within tank.php
    });


    // --- Role 0 (Admin) Only ---
    Route::group(['middleware' => ['role:2']], function () {
        Route::get('/Rates', [App\Http\Controllers\HomeController::class, 'rate'])->name('rate');
        Route::post('/Rates', [App\Http\Controllers\HomeController::class, 'RateUpdate'])->name('RateUpdate');
        Route::get('/Logs', [App\Http\Controllers\HomeController::class, 'Ratelogs'])->name('Ratelogs');
        // Add any other admin-specific web routes here
    });
    // --- Role 0 (SuperAdmin) Only ---



});
Route::group(['middleware' => ['role:0']], function () {
    Route::get('/settings', [DashboardController::class, 'settingsPageGet'])->name('index.settings');
    // Add any other admin-specific web routes here
});
Route::get('/admin/tank-shift-start', [TankShiftController::class, 'runSeeder'])
    ->name('admin.tank-shift.start');
Route::view('/summary-report-form', 'summary-report-form')->name('summary-report-form');
Route::view('/stock-report', 'stock_report_form')->name('StockReportForm');
Route::view('/all-reports', 'All_reports')->name('all-reports');
// Route::get('/all-reports', [SummmaryReportController::class, 'show'])->name("index");

// Summary Report Routes
Route::get('/summary-reports', [SummmaryReportController::class, 'index'])->name('summary-reports.index');
Route::get('/summary-reports/{id}', [SummmaryReportController::class, 'show'])->name('summary-reports.show');
Route::get('/pump-stream',[App\Http\Controllers\PumpStreamController::class, 'stream']);
Route::get('/summary-stream',[App\Http\Controllers\PumpStreamController::class, 'summaryStream']);
// routes/web.php ya routes/api.php (better api.php me daalo)
//Route::view('/summary-report-form/{id}', 'summary-report-form')->name('summary-report-form.edit');
Route::get('/comparison-report/{calendarId}', [SummmaryReportController::class, 'comparisonReport'])->name('comparison.report');;
Route::post('/dispenser/command', [App\Http\Controllers\PumpStreamController::class, 'runCommand']);
//Route::get('/dispenser/config-read', [App\Http\Controllers\PumpStreamController::class, 'runConfigRead']);

// Route::prefix('api')->namespace('API')->group(function () { // Keep commented section as is
//     Route::post('updateTime', [Controllers\ApiController::class,'updateTime']);
//     Route::get('/sales_data', [ApiController::class, 'salesData'])->name('listSales');
//     Route::get('/products', [ApiController::class, 'getAllProducts']);
//     Route::get('/payment-methods', [ApiController::class, 'getAllPaymentMethods']);
//     Route::get('/customers', [ApiController::class, 'getAllCustomers']);
//     Route::get('/nozzles', [ApiController::class, 'getAllNozzles']);

//     Route::middleware(['auth', 'role:5'])->group(function () {
//         Route::get('/employees', [Controllers\ApiController::class, 'employees']);
//         // Add your routes that require a role of at least 5 here
//         Route::get('/shift/check/{pumpId}', [ShiftController::class, 'checkShift']);
//         Route::post('/shift/update', [ShiftController::class, 'updateShift']);
//         Route::post('/shift/create', [ShiftController::class, 'createShift']);
//         Route::get('/shift/close/{id}', [ShiftController::class, 'closeShift']);
//         Route::get('/shift/closeAllShifts', [ShiftController::class, 'closeAllShifts']);
//         Route::get('/shift/shiftStates/{id}', [ShiftController::class, 'shiftStates']);
//         Route::get('/shift/shift_logs', [ShiftController::class, 'shiftLogs']);
//         Route::get('/customer/{id}', [Controllers\ApiController::class, 'customerLimit']);
//         Route::get('/customer/{id}/print', [Controllers\ApiController::class, 'customerPrintLimit']);
//             // Tank management routes
//     Route::get('/tanks', [TankController::class, 'index']);
//     Route::post('/tanks', [TankController::class, 'store']);
//     Route::put('/tanks/{id}', [TankController::class, 'update']);
//     Route::delete('/tanks/{id}', [TankController::class, 'destroy']);
//     Route::post('/tanks/{id}/add-stock', [TankController::class, 'addStock']);
//     Route::get('/tanks/history', [TankController::class, 'stockHistory']);
//     Route::post('/tanks/{id}/upload-dip-chart', [TankController::class, 'uploadDipChart']);
//     Route::get('/tanks/{id}/dip-chart', [TankController::class, 'retreiveDipChart']);

//     });
// });

Route::get('/close', [TankShiftController::class, 'closeByTank'])
    ->name('close');
Route::view('shift-dashboard', 'example_dashboard')->name('example-dashboard');
Route::get('/optimize-clear', function () {
    \Artisan::call('optimize:clear');
    return 'Application cache cleared!';
})->name('optimize.clear');
Route::get('/exmaple-shift', function(){
    return view('shift2');
});

Route::get('/admin-login',function () {
    return redirect()->route('user-management.index');
})->name('admin.login');

// User Management Routes with Special Token
Route::group([
    'middleware' => ['role:0'],
    'prefix' => 'admin'
], function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('user-management.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('user-management.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('user-management.store');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
    Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('user-management.update');
    Route::patch('/users/{user}/password', [UserManagementController::class, 'updatePassword'])->name('user-management.update-password');
    Route::patch('/users/{user}/toggle-block', [UserManagementController::class, 'toggleBlock'])->name('user-management.toggle-block');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');

    //bypass routes
    Route::get('/bypass-limit', [BypassController::class, 'showBypassLimits'])->name('bypass.limit.index');
    Route::post('/bypass-limit', [BypassController::class, 'store'])->name('admin.bypass.limit.store');
    Route::get('/bypass-limits/{id}/edit', [BypassController::class, 'edit'])->name('admin.bypass-limits.edit');
    Route::post('/bypass-limits/{id}/update', [BypassController::class, 'update'])->name('admin.bypass-limits.update');
    Route::delete('/bypass-limits/{id}', [BypassController::class, 'destroy'])->name('admin.bypass-limits.destroy');
    Route::post('/bypass-unlock', [BypassController::class, 'unlock'])->name('admin.bypass.unlock');

});

// Test route for checking .env access
Route::get('/test-env', function() {
    return 'SUPER_ADMIN_TOKEN from config: ' . config('app.super_admin_token');
});


Route::get('/sql-errors', function () {
    $path = storage_path('logs/laravel.log');

    if (!File::exists($path)) {
        return "Log file not found!";
    }

    $today = date("Y-m-d"); // aaj
    $yesterday = date("Y-m-d", strtotime("-1 day")); // kal

    // current page
    $page = request()->get('page', 1);

    $lines = explode("\n", File::get($path));

    // filter
    $sqlErrors = array_filter($lines, function ($line) use ($today, $yesterday) {
        return str_contains($line, 'SQLSTATE') &&
            (str_contains($line, $today) || str_contains($line, $yesterday));
    });

    // group by date
    $grouped = [
        $today => [],
        $yesterday => []
    ];

    foreach ($sqlErrors as $line) {
        if (str_contains($line, $today)) {
            $grouped[$today][] = $line;
        } elseif (str_contains($line, $yesterday)) {
            $grouped[$yesterday][] = $line;
        }
    }

    // decide which day's errors to show
    $currentDate = $page == 1 ? $today : $yesterday;
    $errors = $grouped[$currentDate] ?? [];

    return view('logs', [
        'errors' => $errors,
        'currentDate' => $currentDate,
        'today' => $today,
        'yesterday' => $yesterday,
        'page' => $page
    ]);
});

Route::get('/setup', [SetupController::class, 'index'])->name('setup.index');
Route::post('/setup/create-database', [SetupController::class, 'runExternalCommand'])->name('setup.create');
Route::post('/setup/reset_no_db', [SetupController::class, 'reset'])->name('setup.reset_no_db');
Route::post('/setup/reboot_no_db', [SetupController::class, 'reboot'])->name('setup.reboot_no_db');
Route::post('/pumps/save-display', [Controllers\PumpStreamController::class, 'saveDisplayName'])->name('pumps.saveDisplay');



Route::get('/direct_login', [LoginController::class, 'directLogin'])->name('direct.login');

//only atg
Route::middleware(['auth'])->group(function () {
    Route::get('atg/setup', function () {
        return view('setupAtg');
    })->name('atg/setup');
    Route::get('/purchaseReport/{tank_id}', [AtgController::class, 'purchaseReport']);
    Route::get('/adjustment_report/{id}', [AtgController::class, 'adjustmentReport'])
        ->name('adjustment.report');
    Route::get('/alarm-history', [AtgController::class, 'alarmHistory'])
        ->name('alarm.history');
    Route::get('/alarm-print', [AtgController::class, 'alarmPrint'])
        ->name('alarm.print');
    Route::get('/atg/leakage-report', [AtgController::class,'leakageReport'])->name('atg.leakage.report');

});



