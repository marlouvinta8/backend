<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\Api\MemberController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// for member account
Route::get('member', [MemberController::class, 'index']);
Route::post('member', [MemberController::class, 'store']);
Route::get('member/{id}', [MemberController::class, 'show']);
Route::get('member/{id}/edit', [MemberController::class, 'edit']);
Route::put('member/{id}/edit', [MemberController::class, 'update']);
Route::delete('member/{id}/delete', [MemberController::class, 'destroy']);


// for admin account
Route::get('login', [AdminController::class, 'index']);
Route::post('login', [AdminController::class, 'store']);
Route::get('login/{id}/edit', [MemberController::class, 'edit']);