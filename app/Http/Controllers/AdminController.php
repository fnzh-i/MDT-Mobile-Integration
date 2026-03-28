<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {

        return view('admin-dashboard', ['section' => 'dashboard']);
    }
    public function createLicense()
    {
        return view('admin-dashboard', ['section' => 'create-license']);
    }
    public function createVehicles()
    {
        return view('admin-dashboard', ['section' => 'create-vehicles']);
    }
    public function createUsers()
    {
        return view('admin-dashboard', ['section' => 'create-users']);
    }
    public function searchLicense()
    {
        return view('admin-dashboard', ['section' => 'search-license']);
    }
    public function searchVehicles()
    {
        return view('admin-dashboard', ['section' => 'search-vehicles']);
    }
    public function searchUsers()
    {
        return view('admin-dashboard', ['section' => 'search-users']);
    }

    public function settings()
    {
        return view('admin-dashboard', ['section' => 'settings']);
    }
}