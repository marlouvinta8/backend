<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\ReservationsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// for member account
Route::get('member', [MemberController::class, 'index']);//->middleware('isLoggedIn');
Route::post('member', [MemberController::class, 'store']);//->middleware('isLoggedIn');
Route::post('login/member', [MemberController::class, 'login'])->name('login/member');
Route::get('member/{id}', [MemberController::class, 'show']);
Route::get('member/{id}/edit', [MemberController::class, 'edit']);
Route::put('member/{id}/edit', [MemberController::class, 'update']);
Route::delete('member/{id}/delete', [MemberController::class, 'destroy']);


// for admin account
Route::get('login', [AdminController::class, 'index']);
Route::post('login', [AdminController::class, 'store'])->name('login');
Route::get('login/{id}/edit', [AdminController::class, 'edit']);
Route::put('login/{id}/edit', [AdminController::class, 'update']);
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');



//for promo

Route::get('promo', [PromoController::class, 'index']);
Route::post('promo', [PromoController::class, 'store']);
Route::get('promo/{id}', [PromoController::class, 'show']);
Route::put('promo/{id}/edit', [PromoController::class, 'update']);
Route::delete('promo/{id}/delete', [PromoController::class, 'destroy']);

//for reservation

Route::get('reservation', [ReservationsController::class, 'index']);
Route::post('reservation', [ReservationsController::class, 'store']);
Route::post('reservation', [ReservationsController::class, 'approval']);

Route::get('product', [ProductController::class, 'index']);
Route::post('product', [ProductController::class, 'store']);
Route::get('product/{id}', [ProductController::class, 'show']);
Route::get('product/{id}/edit', [ProductController::class, 'edit']);
Route::put('product/{id}/edit', [ProductController::class, 'update']);
Route::delete('product/{id}/delete', [ProductController::class, 'destroy']);

Route::get('services', [ServicesController::class, 'index']);
Route::post('services', [ServicesController::class, 'store']);
Route::get('services/{id}', [ServicesController::class, 'show']);
Route::get('services/{id}/edit', [ServicesController::class, 'edit']);
Route::put('services/{id}/edit', [ServicesController::class, 'update']);
Route::delete('services/{id}/delete', [ServicesController::class, 'destroy']);

Route::resource('cart', CartController::class);