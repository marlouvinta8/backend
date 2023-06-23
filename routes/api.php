<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\ReservationController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// for member account
Route::get('member', [MemberController::class, 'index'])->middleware('isLoggedIn');
Route::post('member', [MemberController::class, 'store'])->middleware('isLoggedIn');
Route::get('member/{id}', [MemberController::class, 'show']);
Route::get('member/{id}/edit', [MemberController::class, 'edit']);
Route::put('member/{id}/edit', [MemberController::class, 'update']);
Route::delete('member/{id}/delete', [MemberController::class, 'destroy']);


// for admin account
Route::get('login', [AdminController::class, 'index']);
Route::post('login', [AdminController::class, 'store'])->name('store');
Route::get('login/{id}/edit', [AdminController::class, 'edit']);
Route::put('login/{id}/edit', [AdminController::class, 'update']);
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

//for promo

Route::get('promo', [PromoController::class, 'index']);
Route::post('promo', [PromoController::class, 'store']);
Route::get('promo/{id}', [PromoController::class, 'show']);
Route::put('promo/{id}/edit', [PromoController::class, 'update']);
Route::delete('promo/{id}/delete', [PromoController::class, 'destroy']);

//for reservation

Route::get('reservation', [ReservationsController::class, 'index']);
Route::post('reservation', [ReservationController::class, 'store']);
Route::post('reservation', [ReservationController::class, 'approval']);

Route::get('product', [ProductController::class, 'index']);
Route::post('product', [ProductController::class, 'store']);