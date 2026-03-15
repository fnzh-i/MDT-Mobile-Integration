<?php
namespace App\Core\Controllers;

use App\Services\VehicleService;
use App\DTOs\CreateVehicleRequest;
use App\Enums\RegExpiryEnum;
use App\Enums\RegStatusEnum;
use DateTime;
use Throwable;

class VehicleController extends BaseController {
    private VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService) {
        $this->vehicleService = $vehicleService;
    }

    public function create(): void {
        $data = $this->getJsonInput();

        try {
            $request = new CreateVehicleRequest(
                $data['license_number'],
                $data['plate_number'],
                $data['mv_file_number'],
                $data['vin'],
                $data['make'],
                $data['model'],
                (int)$data['year'],
                $data['color'],
                new DateTime($data['issue_date']),
                RegExpiryEnum::from((int)$data['expiry_option']),
                RegStatusEnum::from($data['reg_status'])
            );

            $vehicleId = $this->vehicleService->createVehicle($request);

            $this->sendResponse(["vehicle_id" => $vehicleId], 201, "Vehicle created successfully.");

        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 400);
        }
    }

    public function search(): void {
        $searchNumber = trim($_GET['search_number'] ?? '');

        if (empty($searchNumber)) {
            $this->sendResponse("Please enter the plate or MV file number.", 400);
            return;
        }

        try {
            $result = $this->vehicleService->searchVehicle($searchNumber);
            $this->sendResponse($result, 200, "Vehicle found.");
        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 404);
        }
    }

    public function generateMVFileNumber(): void {
        try {
            $mvFileNumber = $this->vehicleService->generateMVFileNumber();
            $this->sendResponse(["mv_file_number" => $mvFileNumber], 200, "MV file number generated.");
        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 404);
        }
    }
}
?>