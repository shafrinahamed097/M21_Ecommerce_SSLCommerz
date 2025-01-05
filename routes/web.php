<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenAuthenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Brand List
Route::get("/BrandList",[BrandController::class, 'BrandList']);

// Category List
Route::get("/CategoryList", [CategoryController::class, 'CategoryList']);

// Product List
Route::get("/ListProductByCategory/{id}", [ProductController::class, 'ListProductByCategory'] );
Route::get("/ListProductByBrand/{id}", [ProductController::class, 'ListProductByBrand'] );
Route::get("/ListProductByRemark/{remark}", [ProductController::class, 'ListProductByRemark'] );

// Slider
Route::get("/ListProductSlider", [ProductController::class, 'ListProductSlider'] );

// Product Details
Route::get("/ProductDetailsById/{id}", [ProductController::class, 'ProductDetailsById'] );
Route::get("/ListProductReviewByProduct/{product_id}", [ProductController::class, 'ListReviewByProduct'] );

// Policy 
Route::get("/PolicyByType/{type}", [PolicyController::class, 'PolicyByType'] );

// User Auth

Route::get("/UserLogin/{UserEmail}", [UserController::class, 'UserLogin']);
Route::get("/VerifyLogin/{UserEmail}/{OTP}", [UserController::class, 'VerifyLogin']);
Route::get('/logout', [UserController::class, 'userLogout']);

// User Profile
Route::post('/CreateProfile', [ProfileController::class, 'CreateProfile'])->middleware([TokenAuthenticate::class]);
Route::get('/ReadProfile', [ProductController::class])->middleware([TokenAuthenticate::class]);

// Product Review
Route::post('/CreateProductReview', [ProductController::class, 'CreateProductReview'])->middleware([TokenAuthenticate::class]);

// Product Wish
Route::get('/ProductWishList', [ProductController::class, 'ProductWishList'])->middleware([TokenAuthenticate::class]);
Route::get('/CreateWishList/{product_id}', [ProductController::class])->middleware([TokenAuthenticate::class]);
Route::get('/RemoveWishList/{product_id}', [ProductController::class, 'RemoveWishList'])->middleware([TokenAuthenticate::class]);
