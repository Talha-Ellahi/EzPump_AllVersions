<?php

use App\Http\Controllers\TankShiftController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShiftController; // Keep ShiftController import if used elsewhere, though not directly here

// --- Role 6 (Shift Lead) & Above ---
// Routes accessible by roles 6, 5, 0
Route::group(['middleware' => ['role:6']], function () {
    Route::view('tank/stock-history', 'tank.stock-history')->name('tank.stock-history');
    Route::view('tank/tanks', 'tank.tanks')->name('tank.tanks');
    Route::view('tank/', 'tank.tanks')->name('tank.list'); // Alias for tanks view
    Route::view('tank/shift', 'tank.shift')->name('tank.shift');
    Route::view('tank/shift-logs', 'tank.shift-logs')->name('tank.shift-logs');
    Route::view('tank/shift-states', 'tank.shift-states')->name('tank.shift-states');
});

// --- Role 0 (Admin) Only ---
// Routes accessible only by role 0
Route::group(['middleware' => ['role:0']], function () {
    Route::view('tank/add-tank', 'tank.add-tank')->name('tank.add-tank');
    Route::view('tank/edit-tank/{id}', 'tank.add-tank')->name('tank.edit-tank'); // Assuming edit uses the same view
    Route::view('tank/dip-chart-upload', 'tank.dip-chart-upload')->name('tank.dip-chart-upload');
    Route::get('/tank/fill-shift-data', [TankShiftController::class, 'insertShiftDataFromShifts'])->name('tank.fill-shift-data'); // Utility route for admin
});
Route::group(['middleware' => ['role:10']], function () {
    Route::view('tank/tanks', 'tank.tanks')->name('tank.tanks');
    Route::view('tank/', 'tank.tanks')->name('tank.list'); // Alias for tanks view
    Route::view('tank/stock-history', 'tank.stock-history')->name('tank.stock-history');

//    Route::view('tank/add-tank', 'tank.add-tank')->name('tank.add-tank');
//    Route::view('tank/edit-tank/{id}', 'tank.add-tank')->name('tank.edit-tank'); // Assuming edit uses the same view
//    Route::view('tank/dip-chart-upload', 'tank.dip-chart-upload')->name('tank.dip-chart-upload');
//    Route::get('/tank/fill-shift-data', [TankShiftController::class, 'insertShiftDataFromShifts'])->name('tank.fill-shift-data'); // Utility route for admin
});
