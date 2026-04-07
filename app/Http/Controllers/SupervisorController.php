<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    public function index()
    {

        return view('supervisor-dashboard', ['section' => 'dashboard']);
    }
    public function searchLicenses()
    {
        return view('supervisor-dashboard', ['section' => 'license-lookup']);
    }
    public function searchVehicles()
    {
        return view('supervisor-dashboard', ['section' => 'vehicle-lookup']);
    }

    public function settings()
    {
        return view('supervisor-dashboard', ['section' => 'settings']);
    }
}
