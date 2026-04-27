<?php

namespace App\Http\Controllers;

use App\Services\{LicenseService, VehicleService, SupportTicketService, UserService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{
    protected LicenseService $licenseService;
    protected VehicleService $vehicleService;
    protected SupportTicketService $supportTicketService;
    protected UserService $userService;

    public function __construct(
        LicenseService $licenseService, 
        VehicleService $vehicleService,
        SupportTicketService $supportTicketService,
        UserService $userService) {
        $this->licenseService = $licenseService;
        $this->vehicleService = $vehicleService;
        $this->supportTicketService = $supportTicketService;
        $this->userService = $userService;
    }

    private function getUserDisplayName()
    {
        $user = Auth::user();
        return $user->first_name . ' ' . $user->last_name;
    }

    public function index()
    {

        return view('supervisor-dashboard', ['section' => 'dashboard']);
    }
    public function searchLicenses(Request $request)
    {
        $query = trim((string) $request->query('query', ''));
        $searchedLicense = null;
        $error = null;

        if ($query !== '') {
            try {
                $response = $this->licenseService->searchLicense($query);
                $searchedLicense = $response->jsonSerialize();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('supervisor-dashboard', [
            'section' => 'license-lookup',
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
                $searchedVehicle = $response->jsonSerialize();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return view('supervisor-dashboard', [
            'section' => 'vehicle-lookup',
            'searchedVehicle' => $searchedVehicle,
            'error' => $error,
        ]);
    }

    public function settings()
    {
        return view('supervisor-dashboard', [
            'section' => 'settings',
            'userName' => $this->getUserDisplayName(),
        ]);
    }

    public function support()
    {
        return view('supervisor-dashboard', [
            'section' => 'settings',
            'userName' => $this->getUserDisplayName(),
        ]);
    }

    public function submitSupport(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'message' => 'required|string|min:10',
        ]);

        try {
            $user = Auth::user();
            
            // Create support ticket through service
            $ticketId = $this->supportTicketService->createTicket(
                $user->id,
                $request->category,
                $request->message
            );

            return redirect()->back()->with('success', 'Support ticket #' . $ticketId . ' submitted successfully. An administrator will review your request shortly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error submitting support ticket: ' . $e->getMessage());
        }
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        try {
            $user = Auth::user();

            $this->userService->changePassword(
                $user->username,
                $request->current_password,
                $request->new_password
            );

            return redirect()
                ->route('supervisor-settings')
                ->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('supervisor-settings')
                ->with('error', $e->getMessage());
        }
    }
}
