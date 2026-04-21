<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Driver\DriverTapController;
use App\Http\Controllers\Driver\DriverCheckInController;
use App\Http\Controllers\Passenger\PassengerQueueController;
use App\Http\Controllers\Allocation\AssignPassengerController;
use App\Http\Controllers\Driver\DriverCheckOutController;
use App\Http\Controllers\Driver\DriverBreakController;
use App\Http\Controllers\Driver\DriverOffDutyController;
use App\Http\Controllers\Allocation\DriverTimeoutController;
use App\Http\Controllers\Display\DisplayController;
use App\Http\Controllers\Display\DisplayWaitingQueueController;
use App\Http\Controllers\Dashboard\DriverDashboardController;
use App\Http\Controllers\Dashboard\DriverStatsController;
use App\Http\Controllers\Driver\DriverDeferController;
use App\Http\Controllers\Admin\DriverAdminController;
use App\Http\Controllers\Admin\PassengerTypeAdminController;
use App\Http\Controllers\Admin\VehicleCategoryAdminController;
use App\Http\Controllers\Dashboard\QueueProgressController;
use App\Http\Controllers\Dashboard\DeferStatisticsController;
use App\Http\Controllers\Dashboard\DriverStatusController;
use App\Http\Controllers\Admin\DriverCardAdminController;
use App\Http\Controllers\Reporting\ReportingController;


/*********************
 * API ROUTES
 * *******************
 * Semua route API ditempatkan di sini dengan prefix /api
 * Contoh: Route::post('/driver/checkin', DriverCheckInController::class);
 * Route ini akan diakses melalui /api/driver/checkin
 */
Route::prefix('passenger')->group(function () {
    Route::post('/queue', PassengerQueueController::class);
});

Route::prefix('allocation')->group(function () {
    Route::post('/assign', AssignPassengerController::class);
});


Route::prefix('driver')->group(function () {
    Route::post('/defer', DriverDeferController::class);
    Route::post('/break', DriverBreakController::class);
    Route::post('/off-duty', DriverOffDutyController::class);
    Route::post('/checkin', DriverCheckInController::class);
    Route::post('/checkout', DriverCheckOutController::class);
    Route::post('/tap', DriverTapController::class);
});

Route::prefix('display')->group(function () {
    Route::get('/active', DisplayController::class);
    Route::get('/waiting', DisplayWaitingQueueController::class);    
});

Route::prefix('dashboard')->group(function () {
    Route::get('/drivers', DriverDashboardController::class);
    Route::get('/driver-stats', DriverStatsController::class);    
});

Route::prefix('admin')->group(function () {

    // DRIVERS
    Route::get('/drivers', [DriverAdminController::class, 'index']);
    Route::post('/drivers', [DriverAdminController::class, 'store']);
    Route::put('/drivers/{id}', [DriverAdminController::class, 'update']);
    Route::patch('/drivers/{id}/status', [DriverAdminController::class, 'toggleStatus']);

    // VEHICLE CATEGORIES
    Route::get('/vehicle-categories', [VehicleCategoryAdminController::class, 'index']);
    Route::post('/vehicle-categories', [VehicleCategoryAdminController::class, 'store']);
    Route::put('/vehicle-categories/{id}', [VehicleCategoryAdminController::class, 'update']);
    Route::patch('/vehicle-categories/{id}', [VehicleCategoryAdminController::class, 'toggle']);

    // PASSENGER TYPES
    Route::get('/passenger-types', [PassengerTypeAdminController::class, 'index']);
    Route::post('/passenger-types', [PassengerTypeAdminController::class, 'store']);
    Route::put('/passenger-types/{id}', [PassengerTypeAdminController::class, 'update']);
    Route::delete('/passenger-types/{id}', [PassengerTypeAdminController::class, 'destroy']);

    // DASHBOARDS
    Route::get('/dashboard/driver-status', [DriverStatusController::class, 'index']);
    Route::get('/dashboard/queue-progress', [QueueProgressController::class, 'index']);
    Route::get('/dashboard/defer-statistics', [DeferStatisticsController::class, 'index']);

    // DRIVER CARDS
    Route::get(
        '/drivers/{driverId}/cards',
        [DriverCardAdminController::class, 'index']
    );

    Route::post(
        '/drivers/{driverId}/cards',
        [DriverCardAdminController::class, 'store']
    );

    Route::patch(
        '/driver-cards/{id}/toggle',
        [DriverCardAdminController::class, 'toggle']
    );

    Route::delete(
        '/driver-cards/{id}',
        [DriverCardAdminController::class, 'destroy']
    );

});




Route::prefix('reports')->group(function () {
    Route::get('/summary', [ReportingController::class, 'summary']);
    Route::get('/drivers', [ReportingController::class, 'drivers']);
    Route::get('/vip', [ReportingController::class, 'vip']);
    Route::get('/peak-hours', [ReportingController::class, 'peakHours']);
    Route::get('/violations', [ReportingController::class, 'violations']);
});




