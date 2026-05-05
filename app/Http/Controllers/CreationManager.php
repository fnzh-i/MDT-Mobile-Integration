<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DTOs\{CreateLicenseRequest,
                CreateVehicleRequest,
                CreateUserRequest,
                CreateTicketRequest,
                UpdateTicketRequest};
use App\Enums\{LicenseTypeEnum,
                LicenseStatusEnum,
                LicenseExpiryEnum,
                RegExpiryEnum,
                RegStatusEnum,
                UserRolesEnum};
use \App\Services\TicketService;
use App\Repositories\TicketRepository;
use Throwable;
use DateTime;
use Carbon\Carbon;

class CreationManager extends Controller
{
    public function showCreateLicenseForm() 
    {
        // The dot indicates the folder 'create' and the file 'create-license'
        return view('create.create-license'); 
    }

    public function storeLicense(Request $request) {
        $service = app(\App\Services\LicenseService::class);

        try {
            $dto = new CreateLicenseRequest(
                $request->license_number,
                LicenseTypeEnum::from($request->license_type),
                LicenseStatusEnum::Active,
                $request->dl_codes ?? [], // Array from checkboxes
                new DateTime($request->issue_date ?? date('Y-m-d')),
                LicenseExpiryEnum::from((int)$request->expiry_option),
                $request->first_name,
                $request->middle_name,
                $request->last_name,
                $request->suffix,
                new DateTime($request->date_of_birth),
                $request->gender,
                $request->address,
                $request->nationality,
                $request->height,
                $request->weight,
                $request->eye_color,
                $request->blood_type
            );

            $licenseId = $service->createLicense($dto);
            return redirect()->route('admin-dashboard')->with('status', 'License Created Successfully ID: ' . $licenseId);

        } catch (Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function showCreateVehicleForm() 
    {
        // The dot indicates the folder 'create' and the file 'create-vehicle'
        return view('create.create-vehicle'); 
    }

    public function storeVehicle(Request $request) {
        $service = app(\App\Services\VehicleService::class);
        try {
            $dto = new CreateVehicleRequest(
                $request->license_number,
                $request->plate_number,
                $request->mv_file_number,
                $request->vin,
                $request->make,
                $request->model,
                $request->year,
                $request->color,
                new DateTime($request->issue_date ?? date('Y-m-d')),
                RegExpiryEnum::from((int)$request->expiry_option),
                RegStatusEnum::Registered,
            );

            $vehicleId = $service->createVehicle($dto);

            // SUCCESS: Redirect with notification
            return redirect()->route('admin-dashboard')->with('status', 'Vehicle Created Successfully ID: ' . $vehicleId);

        } catch (Throwable $e) {
            // ERROR: Redirect back with error notification
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    // para sa unique mv file number generator sa create vehicle
    public function createUniqueMVFile() {
        $service = app(\App\Services\VehicleService::class);
        
        try {
            $mvFileNumber = $service->generateMVFileNumber();
            
            return response()->json([
                "status" => "success",
                "data" => ["mv_file_number" => $mvFileNumber],
                "message" => "MV file number generated."
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }
    public function showCreateUserForm() 
    {
        // The dot indicates the folder 'create' and the file 'create-vehicle'
        return view('create.create-user'); 
    }

    public function storeUser(Request $request){
        $service = app(\App\Services\UserService::class);
        try{
            $dto = new CreateUserRequest(
                $request->clientNumber,
                $request->first_name,
                $request->middle_name ?? "",
                $request->last_name,
                $request->username,
                $request->email,
                $request->password,
                UserRolesEnum::CIVILIAN,

            );

            $userId = $service->createUser($dto);
            return redirect()->route('admin-dashboard')->with('status', 'User Created Successfully ID: ' . $userId);

        } catch (Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function createUniqueLicenseNumber() {
        $service = app(\App\Services\LicenseService::class);

        try {
            $licenseNumber = $service->generateLicenseNumber();

            return response()->json([
                "status" => "success",
                "data" => ["license_number" => $licenseNumber],
                "message" => "License number generated."
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ], 500);
        }
    }
    
    public function showCreateTicketForm(){
        return view('create.create-ticket');
    }
    public function storeTicket(Request $request) {
        $service = app(\App\Services\TicketService::class);
        
        // DAPAT MULTIPART YUNG ISESEND NG FRONTEND
        $proofImage = null;
        if ($request->hasFile('proof_image')) {
            //$proofImage = file_get_contents($request->file('proof_image')->getRealPath());
            $proofImage = $request->file('proof_image')->store('tickets', 'public');
        }

        try {
            $dto = new CreateTicketRequest(
                $request->license_number,
                (array)($request->violation_id ?? []),
                Carbon::parse($request->date_of_incident),
                $request->place_of_incident,
                $request->notes,
                $proofImage
            );

            $ticket_id = $service->createTicket($dto);

            return redirect()->route('admin-dashboard')->with('status', 'Ticket Created Successfully ID: ' . $ticket_id);
        } catch (Throwable $e) {
            if (isset($proofImage)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($proofImage);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
    public function apiStoreTicket(Request $request) {
        $service = app(\App\Services\TicketService::class);

        try {
            // Normalize keys from Android clients and coerce formats.
            $licenseNumber = $request->input('license_number') ?? $request->input('licenseNumber');
            $placeOfIncident = $request->input('placeOfIncident') ?? $request->input('place_of_incident');
            $violationIds = $request->input('violation_ids') ?? $request->input('violationIds');

            if (is_string($violationIds)) {
                $decoded = json_decode($violationIds, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $violationIds = $decoded;
                }
            }

            if (is_numeric($violationIds)) {
                $violationIds = [(int)$violationIds];
            }

            if (!is_array($violationIds)) {
                $violationIds = [];
            }

            // CreateTicketRequest constructor will throw an error if fields are missing
            $dto = new \App\DTOs\CreateTicketRequest(
            $licenseNumber,
            $violationIds, 
            new \DateTime(),
            $placeOfIncident,
            $request->input('notes'),
            $request->input('proof_image')
            );

            // This is where the ID is generated
            $id = $service->createTicket($dto);

            return response()->json([
                'status' => 'success',
                'id' => $id, // Use $id here
                'message' => 'Ticket created successfully'
            ], 201);

        } catch (\Throwable $e) {
            // Return an actual error response if it fails
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function apiEmailTicket(Request $request) {
        try {
            $ticketId = (int)$request->input('ticket_id');
            $ticketRepo = app(\App\Repositories\TicketRepository::class);
            $userRepo = app(\App\Repositories\UserRepository::class);

            // 1. Get Ticket Data
            $ticket = $ticketRepo->findById($ticketId);
            if (!$ticket) {
                return response()->json(['error' => 'Ticket not found'], 404);
            }

            // 2. Get identifying info
            $licenseNo = $ticket->getLicense()->getLicenseNumber();
            $person = $ticket->getLicense()->getPerson();

            // 3. Find User (Try License first, then Name)
            $user = $userRepo->findByLicenseNumber($licenseNo);
            if (!$user) {
                $user = $userRepo->findByName(
                    $person->getFirstName(),
                    $person->getLastName(),
                    $person->getMiddleName()
                );
            }

            if (!$user) {
                return response()->json(['error' => "No user account linked to license: $licenseNo"], 404);
            }

            // 4. Compose the Citation Details
            $email = $user->getEmail();
            $fullName = $person->getFirstName() . ' ' . $person->getLastName();
            $refNumber = $ticket->getRefNumber();
            $rawDate = $ticket->getCreatedAt();
            $dateObj = ($rawDate instanceof \DateTime) ? $rawDate : new \DateTime($rawDate);
            $formattedDate = $dateObj->format('M d, Y h:i A');
            $location = $ticket->getPlaceOfIncident();
            
            // Build Violation List using the new Repository method
            $violationList = "";
            $totalFine = 0;

            // Fetch items directly from the repository
            $items = $ticketRepo->getTicketItems($ticketId);

            foreach ($items as $item) {
                $violationList .= "- {$item['name']}: PHP " . number_format($item['fine'], 2) . "\n";
                $totalFine += $item['fine'];
            }

            // If the repository total differs from calculated, use the stored total_fine
            $displayTotal = ($totalFine > 0) ? $totalFine : $ticket->getTotalFine();

            $messageBody = "OFFICIAL TRAFFIC CITATION NOTICE\n\n"
                . "Dear $fullName,\n\n"
                . "This notice is to inform you that a traffic violation has been recorded against your driver's license ($licenseNo).\n\n"
                . "--- CITATION DETAILS ---\n"
                . "Reference No : $refNumber\n"
                . "Date & Time  : $formattedDate\n"
                . "Location     : $location\n\n"
                . "--- VIOLATIONS ---\n"
                . $violationList . "\n"
                . "TOTAL AMOUNT DUE: PHP " . number_format($displayTotal, 2) . "\n\n"
                . "Please settle this fine at the nearest LTO office to avoid further penalties.\n\n"
                . "Regards,\n"
                . "LTO Traffic Enforcement Division";

            // 5. Send the Email
            \Illuminate\Support\Facades\Mail::raw($messageBody, function($m) use ($email, $refNumber) {
                $m->to($email)->subject("Traffic Citation Notice - Ticket #$refNumber");
            });

            return response()->json([
                'status' => 'success', 
                'message' => "Citation email sent successfully to $email"
            ]);

        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function destroy($id) {
        $service = app(TicketService::class);
        $success = $service->deleteTicket((int)$id);

        if ($success) {
            return response()->json(['status' => 'success', 'message' => 'Ticket and image deleted!']);
        }
        
        return response()->json([
        'status' => 'error', 
        'message' => 'Check laravel.log for details.'
    ], 500);
    }


    public function settle($id) {
        $service = app(TicketService::class);

        try {
            if (!$id || (int)$id <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Ticket ID provided.'
                ], 400);
            }

            $service->settleTicket((int)$id);

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket #' . $id . ' has been marked as Settled.'
            ]);

        } catch (Throwable $e) {
            $code = ($e->getMessage() === "Ticket not found.") ? 404 : 400;

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function unsettle($id) {
        $service = app(TicketService::class);

        try {
            if (!$id || (int)$id <= 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid Ticket ID provided.'
                ], 400);
            }

            $service->unsettleTicket((int)$id);

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket #' . $id . ' has been reverted to Unsettled.'
            ]);

        } catch (Throwable $e) {
            $code = ($e->getMessage() === "Ticket not found.") ? 404 : 400;

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], $code);
        }
    }

    public function update(Request $request, $id) {
        $service = app(TicketService::class);

        try {
            if (!$id || (int)$id <= 0) {
                return response()->json(['status' => 'error', 'message' => 'Invalid ID'], 400);
            }

            // REORDERED TO MATCH YOUR CONSTRUCTOR: 
            // 1. int $ticketId
            // 2. string $placeOfIncident
            // 3. ?string $notes
            // 4. array $violationIds
            $updateRequest = new \App\DTOs\UpdateTicketRequest(
                (int)$id,
                $request->input('place_of_incident', ''), 
                $request->input('notes'),
                $request->input('violation_ids', [])
            );

            $service->updateTicket($updateRequest);

            return response()->json([
                'status' => 'success',
                'message' => "Ticket #{$id} updated."
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getDetails($id) {
        $repo = app(\App\Repositories\TicketRepository::class);
        $ticket = $repo->findByIdOnly((int)$id);
        
        if (!$ticket) return response()->json(['message' => 'Not found'], 404);
        
        return response()->json($ticket);
    }
}
