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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


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

    public function getDashboardTotals()
    {
        $months = 6;
        $usersTrend = $this->buildMonthlyCumulativeSeries('users', $months);
        $licensesTrend = $this->buildMonthlyCumulativeSeries('licenses', $months);
        $vehiclesTrend = $this->buildMonthlyCumulativeSeries('vehicles', $months);
        $ticketsTrend = $this->buildMonthlyCumulativeSeries('tickets', $months);

        return response()->json([
            'totalUsers' => $this->userRepository->count(),
            'totalLicenses' => $this->licenseRepository->count(),
            'totalVehicles' => $this->vehicleRepository->count(),
            'totalTickets' => $this->ticketRepository->count(),
            'trend' => [
                'labels' => $usersTrend['labels'],
                'users' => $usersTrend['values'],
                'licenses' => $licensesTrend['values'],
                'vehicles' => $vehiclesTrend['values'],
                'tickets' => $ticketsTrend['values'],
            ],
        ]);
    }

    private function buildMonthlyCumulativeSeries(string $table, int $months = 6): array
    {
        $safeMonths = max(2, $months);
        $startMonth = Carbon::now()->startOfMonth()->subMonths($safeMonths - 1);

        $monthKeys = [];
        $labels = [];

        for ($i = 0; $i < $safeMonths; $i++) {
            $monthDate = $startMonth->copy()->addMonths($i);
            $monthKeys[] = $monthDate->format('Y-m');
            $labels[] = $monthDate->format('M Y');
        }

        $baseCount = (int) DB::table($table)
            ->where('created_at', '<', $startMonth->toDateTimeString())
            ->count();

        $monthlyRows = DB::table($table)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as total")
            ->where('created_at', '>=', $startMonth->toDateTimeString())
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get();

        $monthlyMap = [];
        foreach ($monthlyRows as $row) {
            $monthlyMap[$row->month_key] = (int) $row->total;
        }

        $values = [];
        $runningTotal = $baseCount;

        foreach ($monthKeys as $monthKey) {
            $runningTotal += $monthlyMap[$monthKey] ?? 0;
            $values[] = $runningTotal;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
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

    /**
     * Update support ticket status (Open -> In Progress -> Resolved -> Closed)
     */
    public function updateSupportTicketStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:Open,In Progress,Resolved,Closed'
            ]);

            $this->supportTicketService->updateTicketStatus($id, $request->status);

            return redirect()->back()->with('success', 'Ticket status updated to ' . $request->status);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating ticket status: ' . $e->getMessage());
        }
    }

    /**
     * Send password reset email for password change/forgot password tickets
     */
    public function sendPasswordResetEmail(Request $request, $id)
    {
        try {
            $ticket = $this->supportTicketService->getTicketById($id);
            
            if (!$ticket) {
                return redirect()->back()->with('error', 'Ticket not found');
            }

            // Get the user
            $user = \App\Models\User::find($ticket->getUserId());
            
            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }

            // Send password reset email
            \Illuminate\Support\Facades\Password::sendResetLink(['email' => $user->email]);

            // Mark ticket as resolved
            $this->supportTicketService->updateTicketStatus($id, 'Resolved');

            return redirect()->back()->with('success', 'Password reset email sent to ' . $user->email . ' and ticket marked as resolved');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error sending password reset email: ' . $e->getMessage());
        }
    }

    /**
     * Get ticket details (for API/AJAX)
     */
    public function getTicketDetails($id)
    {
        try {
            $ticket = $this->supportTicketService->getTicketById($id);
            
            if (!$ticket) {
                return response()->json(['error' => 'Ticket not found'], 404);
            }

            return response()->json([
                'ticket' => [
                    'id' => $ticket->getId(),
                    'user_id' => $ticket->getUserId(),
                    'category' => $ticket->getCategory(),
                    'message' => $ticket->getMessage(),
                    'status' => $ticket->getStatus(),
                    'admin_response' => $ticket->getAdminResponse(),
                    'created_at' => $ticket->getCreatedAt(),
                    'updated_at' => $ticket->getUpdatedAt(),
                    'email' => $ticket->getUserEmail(),
                    'user_name' => $ticket->getFullName(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send custom email response to user for general inquiries
     */
    public function sendSupportEmail(Request $request, $id)
    {
        try {
            $request->validate([
                'email_subject' => 'required|string|min:5',
                'email_body' => 'required|string|min:10'
            ]);

            $ticket = $this->supportTicketService->getTicketById($id);
            
            if (!$ticket) {
                return redirect()->back()->with('error', 'Ticket not found');
            }

            // Get the user
            $user = \App\Models\User::find($ticket->getUserId());
            
            if (!$user) {
                return redirect()->back()->with('error', 'User not found');
            }

            // Send email using Laravel Mail
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($user, $request) {
                $message->to($user->email)
                    ->subject($request->email_subject)
                    ->setBody($request->email_body, 'text/html');
            });

            // Update ticket with admin response
            $this->supportTicketService->respondToTicket($id, "Email sent: " . $request->email_subject . "\n\n" . $request->email_body);

            return redirect()->back()->with('success', 'Email sent to ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error sending email: ' . $e->getMessage());
        }
    }
}