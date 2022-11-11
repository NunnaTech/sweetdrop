<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/dashboard/admin', [DashboardController::class, 'admin']);
Route::get('/dashboard/dealer', [DashboardController::class, 'dealer']);

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{id}', 'show');
    Route::post('/users', 'store');
    Route::put('/users', 'update');
    Route::delete('/users/{id}', 'destroy');
    Route::get('/users/stores/{id}', 'storesByUser');
});

Route::controller(StoreController::class)->group(function () {
    Route::get('/stores', 'index');
    Route::get('/stores/{id}', 'show');
    Route::post('/stores', 'store');
    Route::put('/stores', 'update');
    Route::delete('/stores/{id}', 'destroy');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{id}', 'show');
    Route::post('/products', 'store');
    Route::put('/products', 'update');
    Route::delete('/products/{id}', 'destroy');
});

Route::controller(OrderController::class)->group(function () {
    Route::get('/orders', 'index');
    Route::get('/orders/user/{id}', 'indexByUser');
    Route::get('/orders/{id}', 'show');
    Route::post('/orders', 'store');
    Route::put('/orders', 'update');
    Route::delete('/orders/{id}', 'destroy');
});

Route::controller(SaleController::class)->group(function () {
    Route::get('/sales', 'index');
    Route::get('/sales/order/{id}', 'indexByOrder');
    Route::get('/sales/{id}', 'show');
    Route::post('/sales', 'store');
    Route::put('/sales', 'update');
    Route::delete('/sales/{id}', 'destroy');
});

Route::controller(ObservationController::class)->group(function () {
    Route::get('/observations', 'index');
    Route::get('/order/{id}/observations', 'indexByOrder');
    Route::get('/observations/{id}', 'show');
    Route::post('/observations', 'store');
    Route::put('/observations', 'update');
    Route::delete('/observations/{id}', 'destroy');
});

Route::controller(StatusController::class)->group(function () {
    Route::get('/status', 'index');
    Route::get('/status/{id}', 'show');
});

Route::controller(RoleController::class)->group(function () {
    Route::get('/roles', 'index');
    Route::get('/roles/{id}', 'show');
    Route::get('/roles/users/dealers', 'indexByRoleDealer');
});
Route::controller(ImageController::class)->group(function () {
    Route::get('/images', 'index');
    Route::get('/observations/{id}/images', 'indexByObservation');
    Route::get('/images/{id}', 'show');
    Route::post('/images', 'store');
    Route::delete('/images/{id}', 'destroy');
});
