<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authmanager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Core\Controllers\LicenseController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register', [Authmanager::class, 'showRegisterForm'])->name('register');
Route::post('/register', [Authmanager::class, 'Register']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/api/license/search', function () {
    // Laravel resolves the Service from the CoreServiceProvider automatically
    $service = app(\App\Services\LicenseService::class);
    $controller = new LicenseController($service);
    return $controller->search();
});

Route::get('/license/create', [Authmanager::class, 'showCreateLicenseForm'])->name('license.create');
Route::post('/license/store',[Authmanager::class, 'storeLicense'])->name('license.store');

Route::get('/vehicle/create', [Authmanager::class, 'showCreateVehicleForm'])->name('vehicle.create');
Route::post('/vehicle/store',[Authmanager::class, 'storeVehicle'])->name('vehicle.store');

// para sa unique mv file number generator sa create vehicle
Route::get('/vehicle/uniquemvfile', [Authmanager::class, 'createUniqueMVFile'])->name('vehicle.uniquemvfile');
Route::get('/user/create', [Authmanager::class, 'showCreateUserForm'])->name('user.create');
Route::post('/user/store',[Authmanager::class, 'storeUser'])->name('user.store');
