<?php
namespace App\Controllers;

use App\Services\VehicleService;
use Throwable;

class VehicleController extends BaseController {
    private VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService) {
        $this->vehicleService = $vehicleService;
    }

    public function search(): void {
        $plate = $_GET['plate'] ?? null;

        if (!$plate) {
            $this->sendResponse("Please enter the plate or MV file number.", 400);
        }

        try {
            $result = $this->vehicleService->searchVehicle($plate);
            $this->sendResponse($result);
        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 404);
        }
    }
}
?>