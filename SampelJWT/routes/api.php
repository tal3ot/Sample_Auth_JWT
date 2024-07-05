<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//API routes

//these don't want any token or any login value so we don't need any middleware concept
Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);


//we need the concept of middleware to protect these method from JWT token value and we need a token value which will generate from the login api
Route::group([
    "middleware" => ['auth:api'] //as we use jwt it will see if the token values is valid so let u or not valid and prevent u
], function () {
    Route::get('profile', [ApiController::class, 'profile']);
    Route::get('refreshToken', [ApiController::class, 'refreshToken']);
    Route::get('logout', [ApiController::class, 'logout']);
});
