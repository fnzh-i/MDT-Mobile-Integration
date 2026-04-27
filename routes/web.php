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
use App\Http\Controllers\SupervisorController;

// Auth::routes(['register' => false]);
// Route::get('/register', [AuthManager::class, 'showRegisterForm'])->name('register');

// --- PUBLIC ROUTES (No Middleware) ---
Route::get('/secret-login', function () { return view('auth.secret-login'); })->name('secret-login');
Route::post('/secret-login', [AuthManager::class, 'Login']);

Route::get('/login-civilian', function () {return view('auth.login-civilian');})->name('login-civilian');
Route::post('/login-civilian', [AuthManager::class, 'LoginCivilian']);

Route::post('apilogin', [AuthManager::class, 'ApiLogin']);

Route::post('/register', [AuthManager::class, 'Register']);
Route::get('/register', function () { return view('auth.register'); })->name('register');

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
    Route::get('/supervisor-dashboard', [SupervisorController::class, 'index'])->name('supervisor-dashboard');
    
    Route::prefix('civilian')->group(function(){ // Civilian manager routes
        Route::get('/license', [CivilianController::class, 'license'])->name('civilian-license');
        Route::get('/vehicles', [CivilianController::class, 'vehicles'])->name('civilian-vehicle');
        Route::get('/violations', [CivilianController::class, 'violations'])->name('civilian-violations');
        Route::get('/settings', [CivilianController::class, 'settings'])->name('civilian-settings');
        Route::get('/support', [CivilianController::class, 'support'])->name('civilian-support');
        Route::post('/support', [CivilianController::class, 'submitSupport'])->name('civilian-support-submit');
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

        // Update and Revoke Routes
        Route::put('/vehicle/update/{id}', [AdminController::class, 'updateVehicle'])->name('admin-update-vehicle');
        Route::patch('/vehicle/revoke/{id}', [AdminController::class, 'revokeVehicle'])->name('admin-revoke-vehicle');
        Route::put('/license/update/{id}', [AdminController::class, 'updateLicense'])->name('admin-update-license');
        Route::patch('/license/revoke/{id}', [AdminController::class, 'revokeLicense'])->name('admin-revoke-license');
        Route::put('/user/update/{id}', [AdminController::class, 'updateUser'])->name('admin-update-user');
        Route::patch('/user/archive/{id}', [AdminController::class, 'archiveUser'])->name('admin-archive-user');

        // for ticket
        Route::post('/ticket/update/{id}', [CreationManager::class, 'update'])->name('ticket.update');
        Route::get('/ticket/details/{id}', [CreationManager::class, 'getDetails']);

        // for update and revoke

        // for settings
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin-settings');
        Route::put('/settings', [AdminController::class, 'updateSettings'])->name('admin-update-settings');

        // for support tickets
        Route::get('/support-tickets', [AdminController::class, 'supportTickets'])->name('admin-support-tickets');
        Route::post('/support-tickets/{id}/status', [AdminController::class, 'updateSupportTicketStatus'])->name('admin-update-ticket-status');
        Route::post('/support-tickets/{id}/email', [AdminController::class, 'sendSupportEmail'])->name('admin-send-support-email');
        Route::post('/support-tickets/{id}/password-reset', [AdminController::class, 'sendPasswordResetEmail'])->name('admin-send-password-reset');
        // for authorization
        // Route::get('/authorize', [AdminController::class, 'authorizeUsers'])->name('admin-authorize');

        // API Routes for Support Tickets
        Route::get('/api/dashboard-totals', [AdminController::class, 'getDashboardTotals']);
        Route::get('/api/support-tickets/{id}', [AdminController::class, 'getTicketDetails']);
    });
    Route:: prefix('supervisor')->group(function(){
        Route::get('/vehicle-lookup', [SupervisorController::class, 'searchVehicles'])->name('supervisor-vehicle-lookup');
        Route::get('/license-lookup', [SupervisorController::class, 'searchLicenses'])->name('supervisor-license-lookup');
        Route::get('/settings', [SupervisorController::class, 'settings'])->name('supervisor-settings');
        Route::put('/settings', [SupervisorController::class, 'updateSettings'])->name('supervisor-update-settings');

        Route::get('/support', [SupervisorController::class, 'support'])->name('supervisor-support');
        Route::post('/support', [SupervisorController::class, 'submitSupport'])->name('supervisor-support-submit');
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