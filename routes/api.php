<?php

use App\Http\Controllers\Authmanager;
use App\Core\Controllers\VehicleController;
use App\Core\Controllers\LicenseController;
use App\Http\Controllers\CreationManager;
use App\Services\VehicleService;
use App\Services\LicenseService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authmanager::class, "apilogin"]);
Route::post('/forgot-password-ticket', [Authmanager::class, "apiForgotPasswordTicket"]);

Route::middleware("auth:sanctum")->group(function () {
    app()->bind(mysqli::class, function () {
        return new mysqli(
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database')
        );
    });

    Route::match(['get', 'post'], "/vehicle/search", function () {
        $service = app(VehicleService::class);
        $controller = new VehicleController($service);
        return $controller->search();
    });

    Route::match(['get', 'post'], "/license/search", function () {
        $service = app(LicenseService::class);
        $controller = new LicenseController($service);
        return $controller->search();
    });
    Route::post('/info', function(){
    return \App\Models\User::all();
    });
    Route::post('/ticket/create', [CreationManager::class, 'apiStoreTicket']);
    Route::post('/ticket/email', [CreationManager::class, 'apiEmailTicket']);
});
