<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\DTOs\CreateLicenseRequest;
use App\Enums\LicenseTypeEnum;
use App\Enums\LicenseStatusEnum;



class Authmanager extends Controller
{
    public function showRegisterForm() 
    {
        return view('auth.register'); 
    }
    function Register(Request $request) {
        $validate = Validator::make($request->all(), [
            'first_name' => "required|string|max:50",
            'middle_name'=> "nullable|string|max:50",
            'last_name'  => "required|string|max:50",
            'username'   => "required|string|unique:users,username",
            'email'      => "required|email|unique:users,email",
            'password'   => "required|min:8|confirmed",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validate->errors()->getMessages()
            ], 200); // Note: Usually 422 is better for validation errors
        }

        $validated = $validate->validated();

        $user = new User();
        // Map the new fields
        $user->first_name = $validated['first_name'];
        $user->middle_name = $validated['middle_name'] ?? null;
        $user->last_name = $validated['last_name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        
        // Set a default role so the database doesn't complain
        $user->role = 'CIVILIAN'; 

        if ($user->save()) {
            return response()->json([
                "status" => "success", // Changed from error to success
                "message" => "User has been registered"
            ], 201); // 201 Created is the standard status code
        }

        return response()->json([
            "status" => "error",
            "message" => "Something went wrong"
        ], 500);
    }
    function Login (Request $request){
        $validate = Validator::make($request->all(),
        [
            'email' => "required",
            'password' => "required|min:8",

        ]);

        if ($validate->fails()){
            return response()->json(
                [
                    "status"=>"error",
                    "message" => $validate->errors()->getMessages()
                    ],200);
        }
        $validated = $validate -> validated();

        if(Auth::attempt(
            [
                'email' => $validated['email'], 
                'password' => $validated['password']
            ])
            ) {
            $user = Auth::user();

            if ($user instanceof User) {
                $token = $user->createToken('mobile_token')->plainTextToken;
            }
                return response()->json(
                    [
                    "status"=>"success",
                    "data"=> ['user'=>$user,'token'=>$token],
                    "message" => "User has been logged in"
                    ],200);
            }
            return response()->json(
            [
                "status"=>"error",
                "message" => "Something went wrong"
                ],200);
    }

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
            (int)$request->expiry_option,
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

    } catch (\Throwable $e) {
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}
}
