<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('sign_in', [UserController::class, 'signIn']);
Route::post('sign_up', [UserController::class, 'signUp']);
