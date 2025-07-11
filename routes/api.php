<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('signup',[AuthController::class,'signup']);
Route::post('login',[AuthController::class,'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout',[AuthController::class,'logout']);
    Route::apiResource('posts',PostController::class);
});


Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('verify-otp', [ForgotPasswordController::class, 'verifyOtp']);



