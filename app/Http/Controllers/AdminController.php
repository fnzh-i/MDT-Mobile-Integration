<?php

namespace App\Http\Controllers;

use App\Services\{LicenseService,
                  UserService,
                  VehicleService,
                  SupportTicketService};
use App\Repositories\{UserRepository,
                      LicenseRepository,
                      VehicleRepository,
                      TicketRepository};
use Illuminate\Http\Request;


class AdminController extends Controller
{
    protected $userService;
    protected $licenseService;
    protected $vehicleService;
    protected $supportTicketService;
    protected $userRepository;
    protected $licenseRepository;
    protected $vehicleRepository;
    protected $ticketRepository;

    public function __construct(UserService $userService,
                                LicenseService $licenseService,
                                VehicleService $vehicleService,
                                SupportTicketService $supportTicketService,
                                UserRepository $userRepository,
                                LicenseRepository $licenseRepository,
                                VehicleRepository $vehicleRepository,
                                TicketRepository $ticketRepository)
    {
        $this->userService = $userService;
        $this->licenseService = $licenseService;
        $this->vehicleService = $vehicleService;
        $this->supportTicketService = $supportTicketService;
        $this->userRepository = $userRepository;
        $this->licenseRepository = $licenseRepository;
        $this->vehicleRepository = $vehicleRepository;
        $this->ticketRepository = $ticketRepository;
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
        $totalUsers = $this->userRepository->count();
        $totalLicenses = $this->licenseRepository->count();
        $totalVehicles = $this->vehicleRepository->count();
        $totalTickets = $this->ticketRepository->count();

        return view('admin-dashboard', [
            'section' => 'dashboard',
            'totalUsers' => $totalUsers,
            'totalLicenses' => $totalLicenses,
            'totalVehicles' => $totalVehicles,
            'totalTickets' => $totalTickets,
        ]);
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
    public function searchLicense(Request $request)
    {
        $query = trim((string) $request->query('query', ''));
        $searchedLicense = null;
        $error = null;

        if ($query !== '') {
            try {
                $response = $this->licenseService->searchLicense($query);
                $data = $response->jsonSerialize();
                // Merge license + person data, ensure license_id exists for routes
                $merged = array_merge($data['license'], $data['person']);
                $merged['license_id'] = $merged['id'];
                $searchedLicense = (object) $merged;
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('admin-dashboard', [
            'section' => 'search-license',
            'searchedLicense' => $searchedLicense,
            'error' => $error,
        ]);
    }
    public function searchVehicles(Request $request)
    {
        $query = trim((string) $request->query('query', ''));
        $searchedVehicle = null;
        $error = null;

        if ($query !== '') {
            try {
                $response = $this->vehicleService->searchVehicle($query);
                $data = $response->jsonSerialize();
                // Merge vehicle + owner data, ensure vehicle_id exists for routes
                $merged = array_merge($data['vehicle'], $data['owner']);
                $merged['vehicle_id'] = $merged['id'];
                $searchedVehicle = (object) $merged;
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('admin-dashboard', [
            'section' => 'search-vehicle',
            'searchedVehicle' => $searchedVehicle,
            'error' => $error,
        ]);
    }
    public function searchUsers(Request $request)
    {
        $query = trim((string) $request->query('query', ''));
        $searchedUser = null;
        $error = null;

        if ($query !== '') {
            try {
                $response = $this->userService->searchUser($query);
                $data = $response->jsonSerialize();
                // Flatten user data, ensure user_id exists for routes
                $merged = $data['user'];
                $merged['user_id'] = $merged['id'];
                $searchedUser = (object) $merged;
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('admin-dashboard', [
            'section' => 'search-users',
            'searchedUser' => $searchedUser,
            'error' => $error,
        ]);
    }

    public function settings()
    {
        return view('admin-dashboard', ['section' => 'settings']);
    }

    public function updateVehicle($id)
    {
        // TODO: Implement vehicle update logic
        return redirect()->back()->with('success', 'Vehicle updated successfully');
    }

    public function revokeVehicle($id)
    {
        // TODO: Implement vehicle revoke logic
        return redirect()->back()->with('success', 'Vehicle revoked successfully');
    }

    public function updateLicense($id)
    {
        // TODO: Implement license update logic
        return redirect()->back()->with('success', 'License updated successfully');
    }

    public function revokeLicense($id)
    {
        // TODO: Implement license revoke logic
        return redirect()->back()->with('success', 'License revoked successfully');
    }

    public function updateUser($id)
    {
        // TODO: Implement user update logic
        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function archiveUser($id)
    {
        // TODO: Implement user archive logic
        return redirect()->back()->with('success', 'User archived successfully');
    }
    public function updateSettings()
    {
        return redirect()->back()->with('success', 'User archived successfully');
        return view('admin-update-settings', ['section' => 'settings']);
    }

    public function supportTickets()
    {
        $tickets = $this->supportTicketService->getAllTickets();
        return view('admin-dashboard', [
            'section' => 'support-tickets',
            'tickets' => $tickets
        ]);
    }
}