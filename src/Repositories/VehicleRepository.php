<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use App\Models\Vehicle;
use App\Enums\RegStatusEnum;
use DateTime;

class VehicleRepository {
    private $conn;
    private LicenseRepository $licenseRepo;

    public function __construct(mysqli $conn,
                                LicenseRepository $licenseRepo) {
        $this->conn = $conn;
        $this->licenseRepo = $licenseRepo;
    }

    public function save(Vehicle $vehicle, int $licenseId) {
        $plateNumber = $vehicle->getPlateNumber();
        $mvFileNumber = $vehicle->getMVFileNumber();
        $vin = $vehicle->getVIN();
        $make = $vehicle->getMake();
        $model = $vehicle->getModel();
        $year = $vehicle->getYear();
        $color = $vehicle->getColor();
        $issueDate = $vehicle->getIssueDate();
        $expiryDate = $vehicle->getExpiryDate();
        $regStatus = $vehicle->getRegStatus();

        $sql = "INSERT INTO vehicles(
            plate_number,
            mv_file_number,
            vin,
            make,
            model,
            year,
            color,
            issue_date,
            expiry_date,
            reg_status,
            license_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param(
            "sssssissssi",
            $plateNumber,
            $mvFileNumber,
            $vin,
            $make,
            $model,
            $year,
            $color,
            $issueDate,
            $expiryDate,
            $regStatus,
            $licenseId
        );

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }
    }

    public function hydrate(array $row): Vehicle {
        $license = $this->licenseRepo->hydrate($row);

        return new Vehicle(
            $row["plate_number"],
            $row["mv_file_number"],
            $row["vin"],
            $row["make"],
            $row["model"],
            (int)$row["year"],
            $row["color"],
            new DateTime($row["issue_date"]),
            RegStatusEnum::from($row["reg_status"]),
            $license,
            (int)$row["vehicle_id"]
        );
    }

    public function existsByPlateNumber(string $plateNumber): bool {
        $sql = "SELECT 1 FROM vehicles WHERE plate_number = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $plateNumber);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function existsByMVFileNumber(string $mvFileNumber): bool {
        $sql = "SELECT 1 FROM vehicles WHERE mv_file_number = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $mvFileNumber);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function existsByVIN(string $vin): bool {
        $sql = "SELECT 1 FROM vehicles WHERE vin = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $vin);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function findByPlateOrMVFile(string $searchNumber): ?Vehicle {
        $sql = "SELECT v.*, l.*, p.* 
                FROM vehicles v
                JOIN licenses l ON v.license_id = l.license_id
                JOIN people p ON l.person_id = p.person_id
                WHERE v.plate_number = ? OR v.mv_file_number = ? 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param(
            "ss",
            $searchNumber,
            $searchNumber
        );

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) return null;

        return $this->hydrate($row);
    }
}
?>