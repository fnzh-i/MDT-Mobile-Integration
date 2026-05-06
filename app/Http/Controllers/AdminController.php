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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
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
    private function getUserDisplayName()
    {
        $user = Auth::user();
        return $user->first_name . ' ' . $user->last_name;
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
            'userName' => $this->getUserDisplayName(),
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
        return view('admin-dashboard', [
            'section' => 'create-license',
            'userName' => $this->getUserDisplayName()
        ]);
    }
    public function createVehicles()
    {
        return view('admin-dashboard', [
            'section' => 'create-vehicle',
            'userName' => $this->getUserDisplayName()
        ]);
    }
    public function createUsers()
    {
        return view('admin-dashboard', [
            'section' => 'create-user',
            'userName' => $this->getUserDisplayName()
        ]);
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
            'userName' => $this->getUserDisplayName(),
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
            'userName' => $this->getUserDisplayName()
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
            'userName' => $this->getUserDisplayName()
        ]);
    }

    public function settings()
    {
        return view('admin-dashboard', [
            'section' => 'settings',
            'userName' => $this->getUserDisplayName(),]);
    }

    public function updateVehicle(Request $request, $id)
    {
        $validated = $request->validate([
            'plate_number' => 'required|string|max:20',
            'make'         => 'required|string|max:50',
            'model'        => 'required|string|max:50',
            'color'        => 'required|string|max:30',
            'reg_status'   => 'required|string',
        ]);

        try {
            // 2. Pass to the service (which then calls the repository we fixed earlier)
            $this->vehicleService->updateVehicle((int)$id, $validated);

            return redirect()->back()->with('success', 'Vehicle registration updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function revokeVehicle($id)
    {
        try {
            $this->vehicleService->updateVehicle((int)$id, [
                'reg_status' => 'Revoked' 
            ]);

            return redirect()->back()->with('success', "Vehicle registration (ID: {$id}) has been successfully REVOKED.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Revocation failed: ' . $e->getMessage());
        }
    }

    public function updateLicense(Request $request, $id)
    {
        $validated = $request->validate([
            'status'           => 'required|string',
            'type'             => 'required|string',
            'dl_codes'         => 'required|string',
            'address'          => 'required|string|max:255',
            'expiry_extension' => 'required|in:0,5,10',
        ]);

        try {
            // 1. Fetch the entity to get current expiry
            $license = $this->licenseService->getLicenseById((int)$id);
            
            // 2. Use Carbon to wrap the DateTime object from your Entity
            $currentExpiry = \Carbon\Carbon::instance($license->getExpiryDate());

            // 3. Calculate new date if extension is picked
            if ($request->expiry_extension > 0) {
                $validated['expiry_date'] = $currentExpiry->addYears((int)$request->expiry_extension)->format('Y-m-d');
            } else {
                $validated['expiry_date'] = $currentExpiry->format('Y-m-d');
            }

            // 4. Update via Service
            $this->licenseService->updateLicense((int)$id, $validated);

            return redirect()->back()->with('success', 'License and Address updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function revokeLicense($id)
    {
        try {
            // We pass the Revoked enum specifically
            $this->licenseService->updateLicenseStatus((int)$id, \App\Enums\LicenseStatusEnum::Revoked);

            return redirect()->back()->with('success', 'License #' . $id . ' has been successfully REVOKED.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Revocation failed: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'role'       => 'required|in:ADMIN,SUPERVISOR,TEAMLEADER,ENFORCER,CIVILIAN',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'username'   => 'required|string|max:255',
            'email'      => 'required|email|max:255',
        ]);

        try {
            $this->userService->updateUser($id, $validated);

            return redirect()->back()->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function archiveUser($id)
    {
        // TODO: Implement user archive logic
        return redirect()->back()->with('success', 'User archived successfully');
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
                ->route('admin-settings')
                ->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin-settings')
                ->with('error', $e->getMessage());
        }
    }

    public function supportTickets()
    {
        $tickets = $this->supportTicketService->getAllTickets();
        return view('admin-dashboard', [
            'section' => 'support-tickets',
            'userName' => $this->getUserDisplayName(),
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
            if (!$ticket) return redirect()->back()->with('error', 'Ticket not found');

            $user = \App\Models\User::find($ticket->getUserId());
            if (!$user) return redirect()->back()->with('error', 'User not found');

            // 1. Generate the secure token for your existing auth page
            $token = \Illuminate\Support\Facades\Password::createToken($user);

            // 2. Generate the URL to your ACTUAL reset page
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

            // 3. Send the email using the working ->html() method
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($user, $resetUrl) {
                $message->to($user->email)
                    ->subject('Password Reset Request - MDT System')
                    ->html("
                        <p>An administrator has initiated a password reset for your account.</p>
                        <p>Please click the button below to set your new password:</p>
                        <a href='{$resetUrl}' style='padding:10px 20px; background:#007bff; color:white; text-decoration:none; border-radius:5px;'>Reset Password</a>
                        <br><br>
                        <p>If the button doesn't work, copy this link: {$resetUrl}</p>
                    ");
            });

            $this->supportTicketService->updateTicketStatus($id, 'Resolved');

            return redirect()->back()->with('success', 'Reset link sent to ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
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

            // --- FIXED SECTION START ---
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($user, $request) {
                $message->to($user->email)
                    ->subject($request->email_subject)
                    ->html($request->email_body);
            });
            // --- FIXED SECTION END ---

            // Update ticket with admin response
            $this->supportTicketService->respondToTicket($id, "Email sent: " . $request->email_subject . "\n\n" . $request->email_body);

            return redirect()->back()->with('success', 'Email sent to ' . $user->email);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error sending email: ' . $e->getMessage());
        }
    }
}