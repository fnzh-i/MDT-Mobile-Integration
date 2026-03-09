<?php
namespace App\Services;

use mysqli;
use Exception;
use InvalidArgumentException;
use App\DTOs\SearchVehicleResponse;
use App\Models\Vehicle;
use App\Repositories\{VehicleRepository, LicenseRepository};

class VehicleService {
    private mysqli $conn;
    private VehicleRepository $vehicleRepo;
    private LicenseRepository $licenseRepo;

    public function __construct(mysqli $conn,
                                VehicleRepository $vehicleRepo,
                                LicenseRepository $licenseRepo) {
        $this->conn = $conn;
        $this->vehicleRepo = $vehicleRepo;
        $this->licenseRepo = $licenseRepo;
    }

    public function createVehicle(Vehicle $vehicle, string $licenseNumber) {
        $this->conn->begin_transaction();

        try {
            $plateNumber = $vehicle->getPlateNumber();
            $mvFileNumber = $vehicle->getMVFileNumber();
            $vin = $vehicle->getVIN();

            if ($this->vehicleRepo->existsByPlateNumber($plateNumber)) {
                throw new Exception("Plate number {$plateNumber} already exists.");
            }

            if ($this->vehicleRepo->existsByMVFileNumber($mvFileNumber)) {
                throw new Exception("MV file number {$mvFileNumber} already exists.");
            }

            if ($this->vehicleRepo->existsByVIN($vin)) {
                throw new Exception("VIN {$vin} already exists.");
            }

            if (!$this->licenseRepo->existsByLicenseNumber($licenseNumber)) {
                throw new Exception("License number {$licenseNumber} does not exist.");
            }

            $licenseId = $this->licenseRepo->findIdByLicenseNumber($licenseNumber);
            $this->vehicleRepo->save($vehicle, $licenseId);

            $this->conn->commit();

        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function searchVehicle(string $searchNumber): SearchVehicleResponse {
        $searchNumber = trim($searchNumber); 

        if (empty($searchNumber)) {
            throw new InvalidArgumentException("Please input the plate of MV file number.");
        }
        
        $vehicle = $this->vehicleRepo->findByPlateOrMVFile($searchNumber);

        if ($vehicle === null) {
            throw new Exception("No vehicles found with {$searchNumber}.");
        }

        return new SearchVehicleResponse ($vehicle);
    }
}
?>