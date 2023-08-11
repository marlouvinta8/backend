<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\ReservationsController;


Route::middleware('auth:sanctum')->group(function () {
Route::post('member', [MemberController::class, 'store']);//->middleware('isLoggedIn');
Route::post('login/member', [MemberController::class, 'login'])->name('login/member');
Route::get('member/{id}', [MemberController::class, 'show']);
Route::get('member/{id}/edit', [MemberController::class, 'edit']);
Route::put('member/{id}/edit', [MemberController::class, 'update']);
Route::delete('member/{id}/delete', [MemberController::class, 'destroy']);

Route::post('login', [AdminController::class, 'store'])->name('login');
Route::get('login/{id}/edit', [AdminController::class, 'edit']);
Route::put('login/{id}/edit', [AdminController::class, 'update']);
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');
Route::get('totalmember', [AdminController::class, 'totalmember']);
Route::get('totalsales', [AdminController::class, 'totalsales']);
Route::get('totalproduct', [AdminController::class, 'totalproduct']);
Route::get('criticalstock', [AdminController::class, 'criticalstock']);

Route::get('sales', [AdminController::class, 'getorder']);
Route::post('sales', [AdminController::class, 'saveorder']);

Route::post('promo', [PromoController::class, 'store']);
Route::get('promo/{id}', [PromoController::class, 'show']);
Route::get('promo/{id}/edit', [PromoController::class, 'edit']);
Route::put('promo/{id}/edit', [PromoController::class, 'update']);
Route::delete('promo/{id}/delete', [PromoController::class, 'destroy']);

Route::get('reservation', [ReservationsController::class, 'index']);

Route::post('product', [ProductController::class, 'store']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::get('product/{id}/edit', [ProductController::class, 'edit']);
Route::put('product/{id}/edit', [ProductController::class, 'update']);
Route::delete('product/{id}/delete', [ProductController::class, 'destroy']);

Route::get('order', [ProductController::class, 'getcart']);
Route::post('order', [ProductController::class, 'addtocart']);
Route::post('cancelorder', [ProductController::class, 'cancelorder']);
Route::post('product/{id}/stockin', [ProductController::class, 'stockin']);
Route::post('product/{id}/stockout', [ProductController::class, 'stockout']);

Route::post('services', [ServicesController::class, 'store']);
Route::get('services/{id}', [ServicesController::class, 'show']);
Route::get('services/{id}/edit', [ServicesController::class, 'edit']);
Route::put('services/{id}/edit', [ServicesController::class, 'update']);
Route::delete('services/{id}/delete', [ServicesController::class, 'destroy']);


Route::get('user', [AuthController::class, 'user']);
Route::post('register', [AuthController::class, 'register']);

});
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
// for member account
Route::get('member', [MemberController::class, 'index']);//->middleware('isLoggedIn');
// for admin account
Route::get('login', [AdminController::class, 'index']);
//for promo
Route::get('promo', [PromoController::class, 'index']);
//for reservation
Route::post('reservation', [ReservationsController::class, 'store']);
// Route::post('reservation/{id}', [ReservationsController::class, 'approval']);
Route::get('product', [ProductController::class, 'index']);
Route::get('services', [ServicesController::class, 'index']);
//Route::resource('cart', CartController::class);


