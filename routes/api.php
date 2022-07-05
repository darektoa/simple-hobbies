<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->group(function() {
    Route::post('/signin', [AuthController::class, 'signIn']);
    Route::post('/signup', [AuthController::class, 'signUp']);
});


/*
    WITH AUTHENTICATION
*/
Route::middleware('auth:api')->group(function() {
    // AUTH
    Route::prefix('/auth')->group(function() {
        Route::post('/signout', [AuthController::class, 'signOut']);
    });

    // MEMBER
    Route::prefix('/members')->group(function() {
        Route::get('/', [MemberController::class, 'index']);
        Route::post('/', [MemberController::class, 'store']);
        Route::get('/{member:id}', [MemberController::class, 'show']);
        Route::put('/{member:id}', [MemberController::class, 'update']);
    });
});