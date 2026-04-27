<?php

use App\Http\Controllers\Authmanager;
use App\Core\Controllers\VehicleController;
use App\Services\VehicleService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authmanager::class, "apilogin"]);
Route::post('/forgot-password-ticket', [Authmanager::class, "apiForgotPasswordTicket"]);

Route::middleware("auth:sanctum")->group(function () {
Route::match(['get', 'post'], "/vehicle/search", function () {
    $service = app(VehicleService::class);
    $controller = new VehicleController($service);
    return $controller->search();
});
    Route::post('/info', function(){
    return \App\Models\User::all();
    });
});
