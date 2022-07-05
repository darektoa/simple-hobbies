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
});