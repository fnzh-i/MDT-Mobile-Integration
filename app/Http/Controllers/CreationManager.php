<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Entities\ViolationEntity;
use App\DTOs\CreateLicenseRequest;
use App\DTOs\CreateVehicleRequest;
use App\DTOs\CreateUserRequest;
use App\DTOs\CreateTicketRequest;
use App\Enums\{LicenseTypeEnum,
               LicenseStatusEnum,
               LicenseExpiryEnum,
               RegExpiryEnum,
               RegStatusEnum,
               UserRolesEnum};
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
                new \DateTime($request->issue_date ?? date('Y-m-d')),
                LicenseExpiryEnum::from((int)$request->expiry_option),
                $request->first_name,
                $request->middle_name,
                $request->last_name,
                $request->suffix,
                new \DateTime($request->date_of_birth),
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

    public function storeVehicle(Request $request){
        $service = app(\App\Services\VehicleService::class);
        try{
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
            return redirect()->route('home')->with('status', 'Vehicle Created Successfully ID: ' . $vehicleId);

        } catch (Throwable $e) {
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
            $proofImage = file_get_contents($request->file('proof_image')->getRealPath());
        }

        try {
            $dto = new CreateTicketRequest(
                $request->license_number,
                (array)($request->violation_id ?? []),
                \Carbon\Carbon::parse($request->date_of_incident),
                $request->place_of_incident,
                $request->notes,
                $proofImage
            );

            $ticket_id = $service->createTicket($dto);

            return redirect()->route('home')->with('status', 'Ticket Created Successfully ID: ' . $ticket_id);
        } catch (Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
