<?php

namespace App\Http\Controllers;

use App\Services\{LicenseService,
                  UserService,
                  VehicleService};
use Illuminate\Http\Request;


class AdminController extends Controller
{
    protected $userService;
    protected $licenseService;
    protected $vehicleService;

    public function __construct(UserService $userService,
                                LicenseService $licenseService,
                                VehicleService $vehicleService)
    {
        $this->userService = $userService;
        $this->licenseService = $licenseService;
        $this->vehicleService = $vehicleService;
    }
    
    public function generateLicenseNumber()
    {
        $number = $this->licenseService->generateLicenseNumber();

        return response()->json([
            'licenseNumber' => $number
        ]);
    }
    public function generateUserClientNumber()
    {
        $number = $this->userService->generateClientNumber();

        return response()->json([
            'clientNumber' => $number
        ]);
    }
    public function generateMVFileNumber(){
        $number = $this->vehicleService->generateMVFileNumber();

        return response()->json([
            'mvFileNumber' => $number
        ]);
    }
    public function generatePlateNumber()
    {
        $number = $this->vehicleService->generatePlateNumber();

        return response()->json([
            'plateNumber' => $number
        ]);
    }
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
        return view('admin-dashboard', ['section' => 'create-vehicle']);
    }
    public function createUsers()
    {
        return view('admin-dashboard', ['section' => 'create-user']);
    }
    public function searchLicense()
    {
        return view('admin-dashboard', ['section' => 'search-licenses']);
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