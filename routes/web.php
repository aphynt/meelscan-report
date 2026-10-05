<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsumptionDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\VisitorsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/login/post', [AuthController::class, 'post'])->name('login.post');

Route::middleware(['auth'])->group(function () {

    // OVERVIEW
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api', [DashboardController::class, 'api'])->name('dashboard.api');
    Route::get('/live-monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/live-monitoring/api', [MonitoringController::class, 'api'])->name('monitoring.api');

    // MEAL MANAGEMENT
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders');
    Route::post('/orders', [OrdersController::class, 'create'])->name('orders.create');

    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
    Route::get('/employees/api', [EmployeesController::class, 'apiEmployees'])->name('employees.api');
    Route::get('/employees/search', [EmployeesController::class, 'search'])->name('employees.search');
    Route::post('/employees/update-healthy',[EmployeesController::class,'updateHealthy'])->name('employees.updateHealthy');
    Route::get('/employees/healthy', [EmployeesController::class, 'healthy'])->name('employees.healthy');
    Route::post('/employees/set-healthy', [EmployeesController::class, 'setHealthy'])->name('employees.setHealthy');
    Route::post('/employees/remove-healthy', [EmployeesController::class, 'removeHealthy'])->name('employees.removeHealthy');

    Route::get('/visitors', [VisitorsController::class, 'index'])->name('visitors.index');
    Route::get('/visitors/api', [VisitorsController::class, 'api'])->name('visitors.api');

    Route::get('/consumption-data', [ConsumptionDataController::class, 'index'])->name('consumptionData');
    Route::get('/consumption-data/api', [ConsumptionDataController::class, 'apiConsumption'])->name('consumptionData.api');
    Route::get('/consumption-data/photo/{id}', [ConsumptionDataController::class, 'showPhoto']);
    Route::delete('/consumption-data/delete/{id}', [ConsumptionDataController::class, 'destroy'])->name('consumptionData.destroy');
    Route::post('/consumption-data/addManual', [ConsumptionDataController::class, 'addManual'])->name('consumptionData.addManual');
    Route::get('/consumption-data/export', [ConsumptionDataController::class, 'exportExcel'])->name('consumptionData.export');

    // ANALYTICS
    Route::get('/analytics/consumption', [AnalyticsController::class, 'consumption'])->name('analytics.consumption');
    Route::get('/analytics/peak-hours', [AnalyticsController::class, 'peakHours'])->name('analytics.peakHours');
    Route::get('/analytics/order-types', [AnalyticsController::class, 'orderType'])->name('analytics.orderType');
    Route::get('/analytics/ratings', [AnalyticsController::class, 'rating'])->name('analytics.rating');
    Route::get('/analytics/face-verification', [AnalyticsController::class, 'faceVerification'])->name('analytics.faceVerification');
    Route::get('/analytics/api', [AnalyticsController::class, 'data'])->name('analytics.api');

    // REPORTS
    Route::get('/reports/daily', [ReportsController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/daily/api', [ReportsController::class, 'dailyApi'])->name('reports.daily.api');
    Route::get('/reports/monthly', [ReportsController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/monthly/api', [ReportsController::class, 'monthlyApi'])->name('reports.monthly.api');
    Route::get('/reports/employee-history', [ReportsController::class, 'employeeHistory'])->name('reports.employeeHistory');
    Route::get('/reports/employee-history/api', [ReportsController::class, 'employeeApi'])->name('reports.employeeHistory.api');

    // MASTER DATA
    Route::get('/master/meal-plan', [MasterDataController::class, 'mealPlan'])->name('master.mealPlan');
    Route::post('/master/meal-plan', [MasterDataController::class, 'storeMealPlan'])->name('master.mealPlan.store');
    Route::delete('/master/meal-plan/{id}', [MasterDataController::class, 'destroyMealPlan'])->name('master.mealPlan.destroy');
    Route::get('/master/food-category', [MasterDataController::class, 'foodCategory'])->name('master.foodCategory');
    Route::get('/master/mess-location', [MasterDataController::class, 'messLocation'])->name('master.messLocation');

    // SYSTEM
    Route::get('/settings', [SystemController::class, 'settings'])->name('system.settings');
    Route::post('/settings', [SystemController::class, 'updateSettings'])->name('system.settings.update');
    Route::get('/user-management', [SystemController::class, 'users'])->name('system.users');
});
