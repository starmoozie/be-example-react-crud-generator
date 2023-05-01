<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    Auth\AuthController,
    ProfileController,
    UserController,
    SupplierController,
    CustomerController,
    ProductCategoryController,
    PaymentMethodController,
    ProductController,
    SaleController
};

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

Route::group([
    'prefix' => 'v1'
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::group([
        'middleware' => ['auth:api']
    ], function () {

        Route::group([
            'prefix' => 'profile'
        ], function () {
            Route::get('', [ProfileController::class, 'show'])->name('show.profile');
            Route::post('', [ProfileController::class, 'update'])->name('update.profile');
            Route::post('upload', [ProfileController::class, 'upload'])->name('upload.profile');
        });

        Route::apiResources([
            'users' => UserController::class,
            'suppliers' => SupplierController::class,
            'customers' => CustomerController::class,
            'product-categories' => ProductCategoryController::class,
            'payment-methods' => PaymentMethodController::class,
            'products' => ProductController::class,
            'sales' => SaleController::class,
        ]);
    });
});
