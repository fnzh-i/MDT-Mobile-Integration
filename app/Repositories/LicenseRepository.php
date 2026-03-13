<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use DateTime;
use App\Entities\LicenseEntity;
use App\Repositories\PersonRepository;
use App\Enums\{LicenseTypeEnum,LicenseStatusEnum};

class LicenseRepository {
    private mysqli $conn;
    private PersonRepository $personRepo;


    public function __construct(mysqli $conn,
                                PersonRepository $personRepo) {
        $this->conn = $conn;
        $this->personRepo = $personRepo;
    }

    public function save(LicenseEntity $license): int {
        $personId = $license->getPerson()->getId();
        $licenseNumber = $license->getLicenseNumber();
        $licenseType = $license->getLicenseType()->value;
        $licenseStatus = $license->getLicenseStatus()->value;
        $dlCodes = $license->getDLCodesAsString();
        $issueDate = $license->getIssueDate()->format("Y-m-d");
        $expiryDate = $license->getExpiryDate()->format("Y-m-d");

        $sql = "INSERT INTO licenses(
            person_id,
            license_number,
            license_type,
            license_status,
            dl_codes,
            issue_date,
            expiry_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param(
            "issssss",
            $personId,
            $licenseNumber,
            $licenseType,
            $licenseStatus,
            $dlCodes,
            $issueDate,
            $expiryDate
        );

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        return $this->conn->insert_id;
    }

    public function hydrate(array $row): LicenseEntity {
        $person = $this->personRepo->hydrate($row);

        return new LicenseEntity (
            $person,
            $row["license_number"],
            LicenseTypeEnum::from($row["license_type"]),
            LicenseStatusEnum::from($row["license_status"]),
            LicenseEntity::parseDLCodes($row['dl_codes']),
            new DateTime($row['issue_date']),
            new DateTime($row['expiry_date']),
            (int)$row["license_id"]
        );
    }

    public function existsByLicenseNumber(string $licenseNumber): bool {
        $sql = "SELECT 1 FROM licenses WHERE license_number = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $licenseNumber);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function findIdByLicenseNumber(string $licenseNumber): ?int {
        $sql = "SELECT license_id FROM licenses WHERE license_number = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $licenseNumber);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) return null;

        return (int)$row["license_id"];
    }


    public function findByLicenseNumber(string $licenseNumber): ?LicenseEntity {
        $sql = "SELECT l.*, 
                    p.first_name,
                    p.last_name,
                    p.middle_name,
                    p.suffix,
                    p.date_of_birth, 
                    p.gender,
                    p.address,
                    p.nationality,
                    p.height,
                    p.weight,
                    p.eye_color,
                    p.blood_type
                FROM licenses l
                JOIN persons p ON l.person_id = p.person_id
                WHERE l.license_number = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $licenseNumber);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) return null;

        return $this->hydrate($row);
    }

    public function findById(int $licenseId): ?LicenseEntity {
        $sql = "SELECT l.*,
                    p.first_name,
                    p.last_name,
                    p.middle_name,
                    p.suffix,
                    p.date_of_birth, 
                    p.gender,
                    p.address,
                    p.nationality,
                    p.height,
                    p.weight,
                    p.eye_color,
                    p.blood_type
                FROM licenses l
                JOIN persons p ON l.person_id = p.person_id
                WHERE license_id = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("i", $licenseId);

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