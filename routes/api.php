<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\socialAuthController;
use App\Http\Controllers\UsersContoller;

use Illuminate\Support\Facades\Route;

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



// Public Routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/passowrd', 'sendResetLink');
    Route::post('/reset-password', 'reset');
});

// Google Auth
Route::get('/login-google', [socialAuthController::class, 'redirectToProvider']);
Route::get('/auth/google/callback', [socialAuthController::class, 'handleCallback']);

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/category/{id}', [CategoryController::class, 'show']);
Route::get('/categorry/{id}', [CategoryController::class, 'productsCategory']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);

// Products
Route::get('/latest-sale', [ProductController::class, 'getLastSaleProducts']);
Route::get('/latest', [ProductController::class, 'getLatest']);
Route::get('/top-rated', [ProductController::class, 'getTopRated']);

// Cart
// Route::get('/carts', [CartController::class, 'index']);
// Route::middleware('checkCart')->controller(CartController::class)->group(function () {
//     Route::post('/add-to-cart', 'addToCart');
// });

// Protected Routes
Route::middleware('auth:api')->group(function () {
    // Cart
    Route::get('/carts', [CartController::class, 'index']);
    Route::delete('/carts/{id}', [CartController::class, 'destroy']);

    // Check Out
    Route::delete('/place-order', [CheckoutController::class, 'placeorder']);


    // Users
    Route::get('/user', [UsersContoller::class, 'authUser']);
    Route::middleware('checkAdmin')->controller(UsersContoller::class)->group(function () {
        Route::get('/users', 'GetUsers');
        Route::get('/user/{id}', 'getUser');
        Route::post('/user/search', 'search');
        Route::post('/user/edit/{id}', 'editUser');
        Route::post('/user/add', 'addUser');
        Route::delete('/user/{id}', 'destroy');
    });
    // Product Manger
    Route::middleware('checkProductManager')->controller(CategoryController::class)->group(function () {

        // Route::get('/category/{id}', 'show');
        Route::post('/category/search', 'search');
        Route::post('/category/edit/{id}', 'edit');
        Route::post('/category/add', 'store');
        Route::delete('/category/{id}', 'destroy');
    });

    Route::middleware('checkProductManager')->controller(ProductController::class)->group(function () {
        Route::post('/product/search', 'search');
        Route::post('/product/edit/{id}', 'update');
        Route::post('/product/add', 'store');
        Route::delete('/product/{id}', 'destroy');
    });
    Route::middleware('checkProductManager')->controller(ProductImageController::class)->group(function () {
        Route::post('/product-img/add', 'store');
        Route::delete('/product-img/{id}', 'destroy');
    });

    Route::middleware('checkCart')->controller(CartController::class)->group(function () {
        Route::post('/add-to-cart', 'addToCart');
    });

    // Auth
    Route::get('/logout', [AuthController::class, 'logout']);
});
