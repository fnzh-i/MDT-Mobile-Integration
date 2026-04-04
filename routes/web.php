<?php

use App\Core\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\CreationManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Core\Controllers\LicenseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\CivilianController; 
use App\Http\Controllers\HomeController;


// Auth::routes(['register' => false]);
// Route::get('/register', [AuthManager::class, 'showRegisterForm'])->name('register');

// --- PUBLIC ROUTES (No Middleware) ---
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthManager::class, 'Login']);

Route::get('/login-civilian', function () {return view('login-civilian');})->name('login-civilian');
Route::post('/login-civilian', [AuthManager::class, 'LoginCivilian']);

Route::post('apilogin', [AuthManager::class, 'ApiLogin']);

Route::post('/register', [AuthManager::class, 'Register']);
Route::get('/register', function () { return view('register'); })->name('register');

Route::post('/forgot-password', [AuthManager::class, 'forgotPassword'])->name('forgot-password');
Route::post('/support/email', [SupportController::class, 'sendEmail'])->name('support.email');
Route::post('/logout', [AuthManager::class, 'logout'])->name('logout');


// --- PROTECTED ROUTES (Requires Login) ---
Route::middleware(['auth'])->group(function (){
    Route::get('/', function () {
        return view('welcome');
    });

    // Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/admin-dashboard',[AdminController::class, 'index'])->name('admin-dashboard');
    Route::get('/civilian-dashboard', [CivilianController::class, 'index'])->name('civilian-dashboard');
    
    Route::prefix('civilian')->group(function(){ // Civilian manager routes
        Route::get('/license', [CivilianController::class, 'license'])->name('civilian-license');
        Route::get('/vehicles', [CivilianController::class, 'vehicles'])->name('civilian-vehicle');
        Route::get('/violations', [CivilianController::class, 'violations'])->name('civilian-violations');
        Route::get('/settings', [CivilianController::class, 'settings'])->name('civilian-settings');
        Route::get('/support', [CivilianController::class, 'support'])->name('civilian-support');
    });

    Route::get('/api/license/search', function (Request $request) {
        try {
            $service = app(\App\Services\LicenseService::class);
            $controller = new LicenseController($service);
            return $controller->search();
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    Route::get('/api/vehicle/search', function () {
        // api for fetching vehicles from the CoreServiceProvider automatically
        $service = app(\App\Services\VehicleService::class);
        $controller = new VehicleController($service);
        return $controller->search();
    });

    Route::get('/license/search', function () { return view('license-search'); })->name('license.search.page');

    Route::get('/vehicle/search', function () { return view('vehicle-search'); })->name('vehicle.search.page');

    Route::prefix('admin')->group(function () {

        // Create Routes
        Route::get('/create-users', [AdminController::class, 'createUsers'])->name('admin-create-users');
        Route::post('/create-user',[CreationManager::class, 'storeUser'])->name('user.store');
        Route::get('/generate-user-number', [AdminController::class, 'generateUserClientNumber'])->name('admin.generate.user.number');

        Route::get('/create-license', [AdminController::class, 'createLicense'])->name('admin-create-license');
        Route::post('/create-license',[CreationManager::class, 'storeLicense'])->name('license.store');
        Route::get('/generate-license-number', [AdminController::class, 'generateLicenseNumber'])->name('admin.generate.license.number');

        Route::get('/create-vehicle', [AdminController::class, 'createVehicles'])->name('admin-create-vehicle');
        Route::post('/create-vehicle',[CreationManager::class, 'storeVehicle'])->name('vehicle.store');
        Route::get('/generate-mvfile-number', [AdminController::class, 'generateMVFileNumber'])->name('admin.generate.mvfile.number');
        Route::get('/generate-plate-number', [AdminController::class, 'generatePlateNumber'])->name('admin.generate.plate.number');

        // Search Routes
        Route::get('/search-users', [AdminController::class, 'searchUsers'])->name('admin-search-users');
        Route::get('/search-licenses', [AdminController::class, 'searchLicense'])->name('admin-search-license');
        Route::get('/search-vehicles', [AdminController::class, 'searchVehicles'])->name('admin-search-vehicle');

        // for ticket
        Route::post('/ticket/update/{id}', [CreationManager::class, 'update'])->name('ticket.update');
        Route::get('/ticket/details/{id}', [CreationManager::class, 'getDetails']);

        // for settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin-settings');
        
        // for authorization
        // Route::get('/authorize', [AdminController::class, 'authorizeUsers'])->name('admin-authorize');
    });

    // Route::prefix('admin')->group(function(){
    //     Route::get('/license/create', [CreationManager::class, 'showCreateLicenseForm'])->name('license.create');
    //     Route::post('/license/store',[CreationManager::class, 'storeLicense'])->name('license.store');

    //     Route::get('/vehicle/create', [CreationManager::class, 'showCreateVehicleForm'])->name('vehicle.create');
    //     Route::post('/vehicle/store',[CreationManager::class, 'storeVehicle'])->name('vehicle.store');

    //     // para sa unique mv file number generator sa create vehicle
    //     Route::get('/vehicle/uniquemvfile', [CreationManager::class, 'createUniqueMVFile'])->name('vehicle.uniquemvfile');
    //     Route::get('/license/uniquelicensenum', [CreationManager::class, 'createUniqueLicenseNumber'])->name('license.uniquelicensenum');

    //     Route::get('/user/create', [CreationManager::class, 'showCreateUserForm'])->name('user.create');
    //     Route::post('/user/store',[CreationManager::class, 'storeUser'])->name('user.store');

    //     Route::get('/ticket/create', [CreationManager::class, 'showCreateTicketForm'])->name('ticket.create');
    //     Route::post('/ticket/store',[CreationManager::class,'storeTicket'])->name('ticket.store');
    //     Route::post('/ticket/delete/{id}', [CreationManager::class, 'destroy'])->name('ticket.destroy');

    //     Route::post('/ticket/update/{id}', [CreationManager::class, 'update'])->name('ticket.update');
    //     Route::get('/ticket/details/{id}', [CreationManager::class, 'getDetails']);
    // });
 
});