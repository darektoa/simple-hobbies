<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/auth')->group(function() {
    Route::post('/signup', [AuthController::class, 'signUp']);
});