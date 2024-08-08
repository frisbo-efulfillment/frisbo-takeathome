<?php

use App\Http\Controllers\FrisboApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/organizations', [FrisboApiController::class, 'getOrganizations']);
Route::get('/orders', [FrisboApiController::class, 'getOrders']);
