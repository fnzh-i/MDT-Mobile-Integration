<?php

use App\Http\Controllers\Authmanager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [Authmanager::class, "apilogin"]);

Route::middleware("auth:sanctum")->group(function(){
    Route::post('/info', function(){
    return \App\Models\User::all();
    });
});

// for Android Mobile App
Route::middleware('auth.firebase')->group(function() {
    
    Route::get('/android-data', function() {
        return response()->json([
            'message' => 'Hello Android! Your Firebase Token is crap (joke lang).'
        ]);
    });

});