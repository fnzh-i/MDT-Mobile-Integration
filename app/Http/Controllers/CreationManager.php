<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DTOs\{CreateLicenseRequest,
                CreateVehicleRequest,
                CreateUserRequest,
                CreateTicketRequest};
use App\Enums\{LicenseTypeEnum,
                LicenseStatusEnum,
                LicenseExpiryEnum,
                RegExpiryEnum,
                RegStatusEnum,
                UserRolesEnum};
use \App\Services\TicketService;
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
            return redirect()->route('home')->with('status', 'License Created Successfully ID: ' . $licenseId);

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

            // SUCCESS: Return JSON
            return response()->json([
                'status' => 'success',
                'message' => 'Vehicle Created Successfully ID: ' . $vehicleId
            ]);

        } catch (Throwable $e) {
            // ERROR: Return JSON with 400 or 500 status
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
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
                $request->first_name,
                $request->middle_name ?? "",
                $request->last_name,
                $request->username,
                $request->email,
                $request->password,
                UserRolesEnum::CIVILIAN,

            );

            $userId = $service->createUser($dto);
            return redirect()->route('home')->with('status', 'User Created Successfully ID: ' . $userId);

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

            return redirect()->route('home')->with('status', 'Ticket Created Successfully ID: ' . $ticket_id);
        } catch (Throwable $e) {
            if (isset($proofImage)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($proofImage);
            }
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
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
}
