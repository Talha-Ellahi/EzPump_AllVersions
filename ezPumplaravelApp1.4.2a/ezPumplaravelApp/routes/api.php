<?php

use App\Http\Controllers\AtgController;
use App\Http\Controllers\AtgEmailController;
use App\Http\Controllers\BypassController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TankShiftController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\pumpdata;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TankController;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    Route::post('updateTime', [Controllers\ApiController::class,'updateTime']);
    Route::get('/sales', [ApiController::class, 'salesData'])->name('listSales');
    Route::get('/products', [ApiController::class, 'getAllProducts']);
    Route::get('/payment-methods', [ApiController::class, 'getAllPaymentMethods']);
    Route::get('/customers', [ApiController::class, 'getAllCustomers']);
    Route::get('/nozzles', [ApiController::class, 'getAllNozzles']);

    Route::get('/settings/public', [SettingsController::class, 'getAllSettings']);
    Route::get('/sysconfig', [SettingsController::class, 'getSysConfig']);

    Route::post('/disablePumpProcessing', [ShiftController::class, 'disablePumpProcessing']);
    Route::post('/enablePumpProcessing', [ShiftController::class, 'enablePumpProcessing']);
    Route::get('/pumps', [ApiController::class, 'pumps']);
    Route::get('/pumpstates', [ApiController::class, 'pumpstates']);
    Route::get('/stats', [ApiController::class, 'stats']);
    Route::get('/stats/hourly', [ApiController::class, 'getHourlySales']);
    Route::get('/stats/nozzle', [ApiController::class, 'getNozzleSales']);
    Route::get('/shift', [ApiController::class, 'shift']);
    Route::get('/shiftSaleData', [ApiController::class, 'shiftSaleData']);
    Route::get('/tanks/lastfeulstatus', [TankController::class, 'getLastFuelStatus']);

    // Routes previously here were moved to web.php for session authentication

    Route::get('/fuel-status', [ApiController::class, 'getFuelStatus']);
Route::get('/sys-mode', function () { $mode = DB::table('SysConfig')->value('Sys_Mode'); return response()->json(['mode' => $mode]); });
// Add mock route for api/alerts
Route::get('/alerts', [ApiController::class, 'getAlerts']);

    Route::get('/tanks-with-alarm', function () {
        return \App\Models\Tank::whereNotNull('last_alarm')->get();
    });


// --- Role 10 (Cashier) & Above ---
// Routes accessible by roles 10, 6, 5, 0
// Route::group(['middleware' => ['auth:sanctum', 'role:10']], function () {
    Route::get('/customer/{id}', [ApiController::class, 'customerLimit']);
    Route::get('/customer/{id}/print', [ApiController::class, 'customerPrintLimit']);
    Route::get('/getCustomers', [ApiController::class, 'getCustomers']); // Assuming all roles can get customers
    Route::get('/getVehicles', [ApiController::class, 'getVehicles']); // Assuming all roles can get vehicles
    Route::post('/addCreditSlip', [ApiController::class, 'addCreditSlip']); // Assuming cashier needs this
    Route::get('/getPumps', [ApiController::class, 'getPumps']); // Assuming all roles can get pumps
    Route::get('/getProductName', [ApiController::class, 'getProductName']); // Assuming all roles can get product names
    Route::get('/printDuplicate/{shiftTableId}/{saleId}', [pumpdata::class, 'printDuplicate']); // Assuming cashier needs this
// });

// --- Role 6 (Shift Lead) & Above ---
// Routes accessible by roles 6, 5, 0
// Route::group(['middleware' => ['auth:sanctum', 'role:6']], function () {
    Route::get('/employees', [ApiController::class, 'employees']); // Moved from role:5
    Route::get('/shift/check/{pumpId}', [ShiftController::class, 'checkShift']);
    Route::post('/shift/update', [ShiftController::class, 'updateShift']);
    Route::post('/shift/create', [ShiftController::class, 'createShift']);
    Route::match(['get', 'post'], '/shift/close/{id}', [ShiftController::class, 'closeShift']); // Combined GET/POST
    Route::get('/shift/closeAllShifts', [ShiftController::class, 'closeAllShifts']);
    Route::post('/pumps/disable', [ShiftController::class, 'disablePumps']);
    Route::post('/pumps/enable', [ShiftController::class, 'enablePumps']);
    Route::get('/shift/shiftStates/{id}', [ShiftController::class, 'shiftStates']);
    Route::get('/shift/shift_logs', [ShiftController::class, 'shiftLogs']);
    Route::get('/shifts', [ShiftController::class, 'shifts']); // Moved from role:5

    // Tank management routes (excluding admin-only)
    Route::get('/tanks', [TankController::class, 'index']);
    // Route::post('/tanks', [TankController::class, 'store']); // Moved to role:0
    Route::get('/tanks/history', [TankController::class, 'stockHistory']);
Route::get('/tanks/by-product/{productKey}', [TankController::class, 'getTankByProduct']);

// Tank by id (single)
Route::get('/tanks/{tank}/by-tank', [TankController::class, 'getTank']);

// Close shifts by tank
Route::post('/shifts/close-by-tank', [ShiftController::class, 'closeByTank']);
Route::post(
    '/tanks/tank-shifts/close-atg-product/{productKey}',
    [TankShiftController::class, 'closeAtgTankShiftsByProduct']
);

    // Route::put('/tanks/{id}', [TankController::class, 'update']); // Moved to role:0
    // Route::delete('/tanks/{id}', [TankController::class, 'destroy']); // Moved to role:0

    // Tank shift routes
    Route::get('/tanks/tank-shifts', [TankShiftController::class, 'getActiveTankShifts']);
    Route::get('/tanks/tank-shifts/check/{tankId}', [TankShiftController::class, 'checkShift']);
    Route::post('/tanks/tank-shifts/update', [TankShiftController::class, 'updateShift']);
    Route::post('/tanks/tank-shifts/open', [TankShiftController::class, 'openShift']);
    Route::post('/tanks/tank-shifts/close/{id}', [TankShiftController::class, 'closeShift']);
    Route::post('/tanks/tank-shifts/close-all', [TankShiftController::class, 'closeAllShifts']);
    Route::post('/tanks/tank-shifts/close-atg-all', [TankShiftController::class, 'closeAllTankAtgShifts']);
    Route::get('/tanks/tank-shift-logs', [TankShiftController::class, 'getTankShiftLogs']);
    Route::get('/tanks/fuel-status', [TankShiftController::class, 'getTankStatusHistory']); // Corrected namespace
    Route::post('/tanks/{id}/add-stock', [TankController::class, 'addStock']);
    Route::post('/tanks/swap', [TankController::class, 'swapTanks']);
    Route::post('/tanks/unswap', [TankController::class, 'unswapTank']);
    Route::get('/tanks/{id}', [TankController::class, 'show']); // View tank details
    Route::post('/tanks/convert-mm-to-totalizer', [TankShiftController::class, 'convertMmToTotalizer']); // New route for MM to Totalizer conversion
// });
Route::put('/tank/update-stock/{id}', [TankController::class, 'updateStock']);
// --- Role 0 (Admin) Only ---
// Routes accessible only by role 0
// Route::group(['middleware' => ['auth:sanctum', 'role:0']], function () {
    Route::get('/settings', [SettingsController::class, 'getAll']);
    Route::post('/settings/update', [SettingsController::class, 'updateAll']);
Route::post('/settings/update-all', [SettingsController::class, 'updateAllConfig']);


// Admin Tank Routes
    Route::post('/tanks', [TankController::class, 'store']); // Create tank
    Route::put('/tanks/{id}', [TankController::class, 'update']); // Update tank
    Route::delete('/tanks/{id}', [TankController::class, 'destroy']); // Delete tank
    Route::post('/tanks/{id}/upload-dip-chart', [TankController::class, 'uploadDipChart']);
    Route::get('/tanks/{id}/dip-chart', [TankController::class, 'retreiveDipChart']);
// });


// Ayaz


Route::post('/summary-report-form', [App\Http\Controllers\SummmaryReportController::class, 'main'])->name('summary-report');
Route::get('/summary-reports', [App\Http\Controllers\SummmaryReportController::class, 'getall'])->name('summary-reports');
Route::get('/shift-calendars', [App\Http\Controllers\SummmaryReportController::class, 'list']);

//shift alarm route
 Route::get('/shift-alerts',[Controllers\ShiftAlertController::class,'upcomingShiftEndings']);
    Route::get('/locked-users', function() {
        $isLocked = Cache::get('system_locked', false);
        $bypassActiveUntil = Cache::get('bypass_active_until', null);
//dd($isLocked,$bypassActiveUntil);
        return response()->json([
            'locked' => $isLocked,
            'bypass_active_until' => $bypassActiveUntil
        ]);
    });

    Route::get('/emailTest',[Controllers\ShiftAlertController::class,'emailTrigger']);

Route::get('/proxy-stats', function (Request $request) {
    $url = $request->query('url');

    if (!$url) {
        return response()->json(['error' => 'URL required'], 400);
    }

    try {
        $response = Http::withoutVerifying()
            ->timeout(30)
            ->get($url);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Failed to fetch from external API',
                'status' => $response->status(),
                'body' => $response->body()
            ], $response->status());
        }

        // ✅ Pehle try karo JSON hai ya nahi
        $json = json_decode($response->body(), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return response()->json($json, $response->status());
        }

        // ✅ Agar JSON nahi hai to plain text/HTML as it is return karo
        return response($response->body(), $response->status())
            ->header('Content-Type', $response->header('Content-Type') ?? 'text/plain');

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Exception: ' . $e->getMessage()
        ], 500);
    }
});
Route::post('/dispenser/config-read', [App\Http\Controllers\PumpStreamController::class, 'runConfigRead']);
Route::post('/dispenser/config-write', [App\Http\Controllers\PumpStreamController::class, 'runConfigWrite']);
Route::post('/dispenser/append_session', [App\Http\Controllers\PumpStreamController::class, 'appendSession']);
Route::post('/dispenser/detect', [App\Http\Controllers\PumpStreamController::class, 'detectDevice']);
Route::post('/dispenser/detect_atg', [App\Http\Controllers\PumpStreamController::class, 'detectDeviceAtg']);
Route::get('/device/detect-uuids', [App\Http\Controllers\PumpStreamController::class, 'getDetectUUIDs']);
Route::post('/dispenser/update-mode', [App\Http\Controllers\PumpStreamController::class,'updateMode']);
Route::post('/tanks/check-stock', [App\Http\Controllers\TankShiftController::class, 'checkStock']);
Route::prefix('atg')->group(function () {
    Route::post('/config-read', [App\Http\Controllers\PumpStreamController::class, 'runATGConfigRead']);
    Route::post('/config-write', [App\Http\Controllers\PumpStreamController::class, 'runATGConfigWrite']);
});
Route::post('/run-command', [App\Http\Controllers\PumpStreamController::class, 'run']);
Route::get('/sysparams', [App\Http\Controllers\PumpStreamController::class, 'sysparams']);
Route::get('/nwk-config', [App\Http\Controllers\PumpStreamController::class, 'nwkConfig']);
Route::post('/nwk-config/update', [App\Http\Controllers\PumpStreamController::class, 'update']);
Route::post('/setup/reset', [SetupController::class, 'reset'])->name('setup.reset');
Route::post('/setup/reboot', [SetupController::class, 'reboot'])->name('setup.reboot');
Route::post('/setup/service-restart', [SetupController::class, 'serviceRestart'])->name('setup.service-restart');
Route::get('/salesend_master', [App\Http\Controllers\PumpStreamController::class, 'saleMaster']);
Route::get('/atg-config', [TankController::class, 'getTankConfigTest']);
Route::post('/tanks/tank-shifts/update_atg', [TankShiftController::class, 'updateShiftAtg']);
//Route::post('/tanks/tank-shifts/close-manual/{id}', [TankShiftController::class, 'closeSingleManualTank']);
Route::match(['get', 'post'], '/tanks/tank-shifts/close-atg-all-only', [TankShiftController::class, 'closeAtgTankShifts']);
Route::post('/tanks/tank-shifts/close-manual', [TankShiftController::class, 'closeSingleManualTank']);
Route::get('/shift/check-today', [ShiftController::class, 'checkTodayShift']);
// routes/api.php
Route::get('/commands/{id}', [App\Http\Controllers\PumpStreamController::class, 'getCommand']);
Route::prefix('atg')->group(function () {
    Route::get('/system', [AtgController::class, 'getSystem']);
    /* ================= PRODUCTS ================= */
//    Route::get('/products', [AtgController::class,'products']);
    Route::post('/product', [AtgController::class,'addProduct']);
    Route::put('/product/{id}', [AtgController::class,'updateProduct']);
    Route::delete('/product/{id}', [AtgController::class,'deleteProduct']);

    /* ================= VENDORS ================= */
    Route::get('/vendors', [AtgController::class,'vendors']);
    Route::post('/vendor', [AtgController::class,'addVendor']);
    Route::put('/vendor/{id}', [AtgController::class,'updateVendor']);
    Route::delete('/vendor/{id}', [AtgController::class,'deleteVendor']);
    Route::delete('/tank/{id}', [AtgController::class,'tankDelete']);

    /* ================= ALARMS ================= */
    Route::get('/alarm', [AtgController::class,'alarmList']);
    Route::post('/alarm', [AtgController::class,'saveAlarm']);
    Route::post('/shift/update', [AtgController::class, 'updateShift']);
// routes/api.php
    Route::get('/tanks/{tankId}/chart-data', [AtgController::class, 'getChartData']);
    Route::get('/tanks/{tankId}/latest-chart-data', [AtgController::class, 'getLatestChartData']);
    Route::get('/emails', [AtgEmailController::class, 'index']);
    Route::post('/email', [AtgEmailController::class, 'store']);
    Route::put('/email/{id}', [AtgEmailController::class, 'update']);
    Route::delete('/email/{id}', [AtgEmailController::class, 'destroy']);
    Route::delete('atg/vendors', [AtgEmailController::class, 'vendor']);
    Route::get('/alarm-settings', function () {
        return \DB::table('atg_alarm_settings')->first();
    });
    Route::post('/leakage/start', [AtgController::class,'startLeakage']);
    Route::post('/leakage/stop', [AtgController::class,'stopLeakage']);
    Route::get('/leakage/status',[AtgController::class,'leakageStatus']);

});
