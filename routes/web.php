<?php

use App\Admin\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
Route::get('auth/login', AuthController::class . '@getLogin');
Route::post('auth/login', AuthController::class . '@postLogin');
Route::get('auth/setting', AuthController::class . '@getSetting');


Route::get('google-authenticator', AuthController::class.'@index');

Route::post('/', AuthController::class.'@googlePost')->name('admin.GoogleAuthenticator');
Route::post('/setUserGoogleAuth', AuthController::class.'@setGoogleAuth')->name('admin.setUserGoogleAuth');