<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\RateController;
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

// Products
Route::get('/latest-sale', [ProductController::class, 'getLastSaleProducts']);
Route::get('/all-latest-sale', [ProductController::class, 'getAllLastSaleProducts']);
Route::get('/latest', [ProductController::class, 'getLatest']);
Route::get('/all-latest', [ProductController::class, 'getAllLatest']);
Route::get('/top-rated', [ProductController::class, 'getTopRated']);
Route::get('/all-top-rated', [ProductController::class, 'getAllTopRated']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::get('/product/showRelated/{id}', [ProductController::class, 'showRelated']);
Route::get('/product-category/{id}', [ProductController::class, 'showCategory']);
Route::post('/product/search', [ProductController::class, 'search']);

// Banners
Route::get('/banner', [BannerController::class, 'index']);

// Rates
Route::get('/rate', [RateController::class, 'index']);
Route::post('/rate/add', [RateController::class, 'store']);
Route::get('/rates/{id}', [RateController::class, 'show']);
Route::get('/rateshow/{id}', [RateController::class, 'rateshow']);
Route::post('/rate/edit/{id}', [RateController::class, 'update']);
Route::delete('/rate/{id}', [RateController::class, 'destroy']);

// Profile
Route::post('/profile-edit/{id}', [UsersContoller::class, 'editProfileUser']);

// Protected Routes
Route::middleware('auth:api')->group(function () {
    // Cart
    Route::get('/carts', [CartController::class, 'index']);
    Route::delete('/carts/{id}', [CartController::class, 'destroy']);
    Route::put('/cart-updateqty/{qty_id}/{scope}', [CartController::class, 'updatequantity']);
    Route::get('/cart-length', [CartController::class, 'cartlength']);

    // Check Out
    Route::post('/place-order', [CheckoutController::class, 'placeorder']);
    Route::get('/get-last-order', [CheckoutController::class, 'getLastOrder']);

    // Address
    Route::get('/address', [AddressController::class, 'index']);
    Route::post('/address/add', [AddressController::class, 'addAddress']);
    Route::put('/address/edit/{id}', [AddressController::class, 'update']);
    Route::delete('/address/delete/{id}', [AddressController::class, 'destroy']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::get('/get-order', [OrderController::class, 'showorders']);
    Route::get('/last-order', [OrderController::class, 'lastorders']);
    Route::post('/orders/edit/{id}', [OrderController::class, 'update']);
    Route::delete('/order/{id}', [OrderController::class, 'destroy']);
    Route::post('/order/cancel/{id}', [OrderController::class, 'cancelorder']);

    // Showing Orders
    Route::get('/get-order-count', [OrderController::class, 'showorderscount']);

    // Banners
    Route::get('/banner/showindashboard', [BannerController::class, 'showindashboard']);
    Route::get('/banner/show/{id}', [BannerController::class, 'showbanner']);
    Route::post('/banner/add', [BannerController::class, 'create']);
    Route::post('/banner/edit/{id}', [BannerController::class, 'update']);
    Route::delete('/banner/delete/{id}', [BannerController::class, 'destroy']);

    // Coupon
    Route::get('/coupon', [CouponController::class, 'index']);
    Route::get('/coupon/{id}', [CouponController::class, 'show']);
    Route::get('/coupon/check/{id}', [CouponController::class, 'checkcoupon']);
    Route::post('/coupon/edit/{id}', [CouponController::class, 'editcoupon']);
    Route::post('/coupon/add', [CouponController::class, 'store']);
    Route::delete('/coupon/delete/{id}', [CouponController::class, 'destroy']);

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
        Route::get('/category-show/{id}', 'showcategory');
        Route::post('/category/search', 'search');
        Route::post('/category/edit/{id}', 'edit');
        Route::post('/category/add', 'store');
        Route::delete('/category/{id}', 'destroy');
    });

    Route::middleware('checkProductManager')->controller(ProductController::class)->group(function () {
        Route::post('/product/edit/{id}', 'update');
        Route::post('/product/add', 'store');
        Route::post('/sizes/add', 'addSizes');
        Route::get('/sizes/{id}', 'showSize');
        Route::put('/sizes/edit/{id}', 'updateSize');
        Route::delete('/size-delete/{id}', 'destroysize');
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
