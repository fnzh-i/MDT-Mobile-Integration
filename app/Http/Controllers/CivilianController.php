<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\{PersonRepository, LicenseRepository, VehicleRepository, TicketRepository};

class CivilianController extends Controller
{
    protected PersonRepository $personRepo;
    protected LicenseRepository $licenseRepo;
    protected VehicleRepository $vehicleRepo;
    protected TicketRepository $ticketRepo;

    public function __construct(
        PersonRepository $personRepo,
        LicenseRepository $licenseRepo,
        VehicleRepository $vehicleRepo,
        TicketRepository $ticketRepo
    ) {
        $this->personRepo = $personRepo;
        $this->licenseRepo = $licenseRepo;
        $this->vehicleRepo = $vehicleRepo;
        $this->ticketRepo = $ticketRepo;
    }

    private function getAuthenticatedUserData()
    {
        $user = Auth::user();
        
        // Find person matching user's name
        $person = $this->personRepo->findByName(
            $user->first_name,
            $user->last_name,
            $user->middle_name ?? null
        );

        return [
            'user' => $user,
            'person' => $person
        ];
    }

    private function getUserDisplayName()
    {
        $user = Auth::user();
        return $user->first_name . ' ' . $user->last_name;
    }

    public function index()
    {
        $data = $this->getAuthenticatedUserData();
        $user = $data['user'];
        $person = $data['person'];

        $licenseData = null;
        $vehicleData = null;

        if ($person) {
            // Get first license for summary
            $license = $this->licenseRepo->findByPersonId($person->getId());
            if ($license) {
                $licenseData = (object)[
                    'licenseNumber' => $license->getLicenseNumber(),
                    'status' => $license->getLicenseStatus()->value,
                    'type' => $license->getLicenseType()->value,
                    'expiryDate' => $license->getExpiryDate()->format('M d, Y'),
                    'firstName' => $person->getFirstName(),
                    'lastName' => $person->getLastName(),
                ];

                // Get first vehicle for summary
                $vehicles = $this->vehicleRepo->findByLicenseId($license->getId());
                if (!empty($vehicles)) {
                    $vehicle = $vehicles[0];
                    $vehicleData = (object)[
                        'make' => $vehicle->getMake(),
                        'model' => $vehicle->getModel(),
                        'plateNumber' => $vehicle->getPlateNumber(),
                        'status' => $vehicle->getRegStatus()->value,
                        'regExpiryDate' => $vehicle->getExpiryDate()->format('M d, Y'),
                    ];
                }
            }
        }

        return view('civilian-dashboard', [
            'section' => 'dashboard',
            'licenseData' => $licenseData,
            'vehicleData' => $vehicleData,
            'userName' => $this->getUserDisplayName(),
        ]);
    }

    public function license()
    {
        $data = $this->getAuthenticatedUserData();
        $person = $data['person'];
        $licenseData = null;

        if ($person) {
            $license = $this->licenseRepo->findByPersonId($person->getId());
            if ($license) {
                $licenseData = (object)[
                    'status' => $license->getLicenseStatus()->value,
                    'firstName' => $person->getFirstName(),
                    'middleName' => $person->getMiddleName(),
                    'lastName' => $person->getLastName(),
                    'suffix' => $person->getSuffix(),
                    'dateOfBirth' => $person->getDateOfBirth()->format('M d, Y'),
                    'gender' => $person->getGender(),
                    'address' => $person->getAddress(),
                    'nationality' => $person->getNationality(),
                    'height' => $person->getHeight(),
                    'weight' => $person->getWeight(),
                    'eyeColor' => $person->getEyeColor(),
                    'bloodType' => $person->getBloodType(),
                    'licenseNumber' => $license->getLicenseNumber(),
                    'licenseType' => $license->getLicenseType()->value,
                    'issueDate' => $license->getIssueDate()->format('M d, Y'),
                    'expiryDate' => $license->getExpiryDate()->format('M d, Y'),
                    'dlCodes' => $license->getDLCodesAsString(),
                ];
            }
        }

        return view('civilian-dashboard', [
            'section' => 'license',
            'licenseData' => $licenseData,
            'userName' => $this->getUserDisplayName(),
        ]);
    }

    public function vehicles(Request $request)
    {
        $data = $this->getAuthenticatedUserData();
        $person = $data['person'];
        $vehicles = [];
        $selectedVehicle = null;

        if ($person) {
            $license = $this->licenseRepo->findByPersonId($person->getId());
            if ($license) {
                $vehicles = $this->vehicleRepo->findByLicenseId($license->getId());
                
                // Handle vehicle search/view
                if ($request->has('plate')) {
                    $searchPlate = $request->input('plate');
                    $foundVehicle = $this->vehicleRepo->findByPlateOrMVFile($searchPlate);
                    if ($foundVehicle && in_array($foundVehicle->getId(), array_column($vehicles, 'vehicle_id'))) {
                        $selectedVehicle = $foundVehicle;
                    }
                }
            }
        }

        $vehicleList = array_map(function($v) use ($person) {
            return (object)[
                'vehicleId' => $v->getId(),
                'make' => $v->getMake(),
                'model' => $v->getModel(),
                'plateNumber' => $v->getPlateNumber(),
                'status' => $v->getRegStatus()->value,
                'expiryDate' => $v->getExpiryDate()->format('M d, Y'),
            ];
        }, $vehicles);

        $selectedVehicleData = null;
        if ($selectedVehicle && $person) {
            $selectedVehicleData = (object)[
                'status' => $selectedVehicle->getRegStatus()->value,
                'mvFileNumber' => $selectedVehicle->getMvFileNumber(),
                'model' => $selectedVehicle->getModel(),
                'color' => $selectedVehicle->getColor(),
                'expiryDate' => $selectedVehicle->getExpiryDate()->format('M d, Y'),
                'plateNumber' => $selectedVehicle->getPlateNumber(),
                'make' => $selectedVehicle->getMake(),
                'year' => $selectedVehicle->getYear(),
                'vin' => $selectedVehicle->getVin(),
                'ownerName' => $person->getFirstName() . ' ' . $person->getLastName(),
                'ownerAddress' => $person->getAddress(),
            ];
        }

        return view('civilian-dashboard', [
            'section' => 'vehicles',
            'vehicles' => $vehicleList,
            'selectedVehicle' => $selectedVehicleData,
            'userName' => $this->getUserDisplayName(),
        ]);
    }

    public function violations(Request $request)
    {
        $user = Auth::user();
        $violations = [];

        // Get violations by lto_client_id
        $tickets = $this->ticketRepo->findByLtoClientId($user->lto_client_id);
        
        $violations = array_map(function($t) {
            return (object)[
                'offence' => $t->getStatus()->value ?? 'Traffic Violation',
                'date' => $t->getDateOfIncident()->format('M d, Y'),
                'place' => $t->getPlaceOfIncident(),
                'notes' => $t->getNotes() ?? 'N/A',
                'fine' => '₱ ' . number_format($t->getTotalFine()),
            ];
        }, $tickets);

        return view('civilian-dashboard', [
            'section' => 'violations',
            'violations' => $violations,
            'userName' => $this->getUserDisplayName(),
        ]);
    }

    public function settings()
    {
        return view('civilian-dashboard', [
            'section' => 'settings',
            'userName' => $this->getUserDisplayName(),
        ]);
    }

    public function support()
    {
        return view('civilian-dashboard', [
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

        $user = Auth::user();

        // In a real application, you would save this to a support_tickets table
        // For now, we'll just redirect back with a success message
        // You can implement ticket creation logic here

        return redirect()->back()->with('success', 'Support ticket submitted successfully. An administrator will review your request shortly.');
    }
}