<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CivilianController extends Controller
{
    public function index()
    {

        return view('civilian-dashboard', ['section' => 'dashboard']);
    }
    public function license()
    {
        return view('civilian-dashboard', ['section' => 'license']);
    }
    public function vehicles()
    {
        return view('civilian-dashboard', ['section' => 'vehicles']);
    }
    public function violations()
    {
        return view('civilian-dashboard', ['section' => 'violations']);
    }
    public function settings()
    {
        return view('civilian-dashboard', ['section' => 'settings']);
    }
}