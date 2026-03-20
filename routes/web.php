<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\CreationManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Core\Controllers\LicenseController;
use App\Http\Controllers\SupportController; 


Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register', [AuthManager::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthManager::class, 'Register']);


Route::get('/login-civilian', function () {return view('login-civilian');})->name('login-civilian');
Route::post('/login-civilian', [AuthManager::class, 'login']);
Route::get('/register', function () { return view('register'); })->name('register');
Route::post('/register', [AuthManager::class, 'register']);
Route::post('/forgot-password', [AuthManager::class, 'forgotPassword'])->name('forgot-password');
Route::post('/support/email', [SupportController::class, 'sendEmail'])->name('support.email');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/api/license/search', function () {
    // Laravel resolves the Service from the CoreServiceProvider automatically
    $service = app(\App\Services\LicenseService::class);
    $controller = new LicenseController($service);
    return $controller->search();
});

Route::get('/license/create', [CreationManager::class, 'showCreateLicenseForm'])->name('license.create');
Route::post('/license/store',[CreationManager::class, 'storeLicense'])->name('license.store');

Route::get('/vehicle/create', [CreationManager::class, 'showCreateVehicleForm'])->name('vehicle.create');
Route::post('/vehicle/store',[CreationManager::class, 'storeVehicle'])->name('vehicle.store');

// para sa unique mv file number generator sa create vehicle
Route::get('/vehicle/uniquemvfile', [CreationManager::class, 'createUniqueMVFile'])->name('vehicle.uniquemvfile');
Route::get('/license/uniquelicensenum', [CreationManager::class, 'createUniqueLicenseNumber'])->name('license.uniquelicensenum');

Route::get('/user/create', [CreationManager::class, 'showCreateUserForm'])->name('user.create');
Route::post('/user/store',[CreationManager::class, 'storeUser'])->name('user.store');

Route::get('/ticket/create', [CreationManager::class, 'showCreateTicketForm'])->name('ticket.create');
Route::post('/ticket/store',[CreationManager::class,'storeTicket'])->name('ticket.store');