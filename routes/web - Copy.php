<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupportController; 

Route::get('/', function () {return view('landing');});
Route::get('/login-civilian', function () {return view('login-civilian');})->name('login-civilian');
Route::post('/login-civilian', [AuthController::class, 'login']);
Route::get('/register', function () { return view('register'); })->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/support/email', [SupportController::class, 'sendEmail'])->name('support.email');