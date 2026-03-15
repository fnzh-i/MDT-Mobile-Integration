<?php
namespace App\Services;

use App\DTOs\CreateVehicleRequest;
use mysqli;
use Exception;
use InvalidArgumentException;
use App\DTOs\SearchVehicleResponse;
use App\Entities\VehicleEntity;
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

    public function createVehicle(CreateVehicleRequest $request): int {
        $this->conn->begin_transaction();

        try {
            $licenseNumber = $request->getLicenseNumber();
            $plateNumber = $request->getPlateNumber();
            $mvFileNumber = $request->getMVFileNumber();
            $vin = $request->getVIN();
            
            if (!$this->licenseRepo->existsByLicenseNumber($licenseNumber)) {
                throw new Exception("License number {$licenseNumber} does not exist.");
            }

            if ($this->vehicleRepo->existsByPlateNumber($plateNumber)) {
                throw new Exception("Plate number {$plateNumber} already exists.");
            }

            if ($this->vehicleRepo->existsByMVFileNumber($mvFileNumber)) {
                throw new Exception("MV file number {$mvFileNumber} already exists.");
            }

            if ($this->vehicleRepo->existsByVIN($vin)) {
                throw new Exception("VIN {$vin} already exists.");
            }

            $issueDate = $request->getIssueDate();
            $expiryOption = $request->getExpiryOption()->value;
            $expiryDate = (clone $issueDate)->modify("+$expiryOption years");

            $vehicle = new VehicleEntity(
                $plateNumber,
                $mvFileNumber,
                $vin,
                $request->getMake(),
                $request->getModel(),
                $request->getYear(),
                $request->getColor(),
                $issueDate,
                $expiryDate,
                $request->getRegStatus()
            );

            $licenseId = $this->licenseRepo->findIdByLicenseNumber($licenseNumber);
            $vehicleId = $this->vehicleRepo->save($vehicle, $licenseId);

            $this->conn->commit();

            return $vehicleId;

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

    public function generateMVFileNumber(): string {
        do {
            $newMVFileNum = sprintf("%04d-%07d", mt_rand(0, 9999), mt_rand(0, 9999999));
            $alreadyExists = $this->vehicleRepo->existsByMVFileNumber($newMVFileNum);
            
        } while ($alreadyExists);

        return $newMVFileNum;
    }
}
?>