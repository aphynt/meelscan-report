<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsumptionDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\OrdersController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login/post', [AuthController::class, 'post'])->name('login.post');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api', [DashboardController::class, 'api'])->name('dashboard.api');

    Route::get('/orders', [OrdersController::class, 'index'])->name('orders');
    Route::post('/orders', [OrdersController::class, 'create'])->name('orders.create');

    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
    Route::get('/employees/api', [EmployeesController::class, 'apiEmployees'])->name('employees.api');
    Route::get('/employees/search', [EmployeesController::class, 'search'])->name('employees.search');

    Route::get('/consumption-data', [ConsumptionDataController::class, 'index'])->name('consumptionData');
    Route::get('/consumption-data/api', [ConsumptionDataController::class, 'apiConsumption'])->name('consumptionData.api');
    Route::get('/consumption-data/photo/{id}', [ConsumptionDataController::class, 'showPhoto']);
    Route::delete('/consumption-data/delete/{id}', [ConsumptionDataController::class, 'destroy'])->name('consumptionData.destroy');
    Route::post('/consumption-data/addManual', [ConsumptionDataController::class, 'addManual'])->name('consumptionData.addManual');
    Route::get('/consumption-data/export', [ConsumptionDataController::class, 'exportExcel'])->name('consumptionData.export');

});

