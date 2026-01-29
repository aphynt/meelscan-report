<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConsumptionApiController;

Route::get('/consumption', [ConsumptionApiController::class, 'index']);
