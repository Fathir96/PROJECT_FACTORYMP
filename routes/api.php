<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductControllerAPI;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\DeliveryController;



Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
// add LogRequestMiddleware
Route::middleware(['auth:sanctum', 'LogRequestMiddleware'])->group(function(){
    Route::apiResource('/products', ProductControllerAPI::class, ['names' => ['index' => 'api.products.index', 'store' => 'api.products.store', 'show' => 'api.products.show', 'update' => 'api.products.update', 'destroy' => 'api.products.destroy']]);
    Route::apiResource('/stores', StoreController::class, ['names' => ['index' => 'api.stores.index', 'store' => 'api.stores.store', 'show' => 'api.stores.show', 'update' => 'api.stores.update', 'destroy' => 'api.stores.destroy']])->middleware('isAdmin');
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/brands', BrandController::class);
    Route::apiResource('/payments', PaymentController::class);
    Route::apiResource('/vouchers', VoucherController::class);
    Route::apiResource('deliveries', DeliveryController::class);
    Route::post('/logout', [UserController::class, 'logout']);
});

Route::fallback(function(){
    return response()->json(['message' => 'Unauthorized.'], 401);
});