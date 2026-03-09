<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use DateTime;
use App\Models\License;
use App\Repositories\PersonRepository;
use App\Enums\{
    LicenseTypeEnum,
    LicenseStatusEnum,
    LicenseExpiryEnum
};

class LicenseRepository {
    private mysqli $conn;
    private PersonRepository $personRepo;


    public function __construct(mysqli $conn,
                                PersonRepository $personRepo) {
        $this->conn = $conn;
        $this->personRepo = $personRepo;
    }

    public function save(License $license, int $personId): int {
        $licenseNumber = $license->getLicenseNumber();
        $licenseType = $license->getLicenseType();
        $licenseStatus = $license->getLicenseStatus();
        $dlCodes = $license->getDLCodesAsString();
        $issueDate = $license->getIssueDate();
        $expiryDate = $license->getExpiryDate();

        $sql = "INSERT INTO licenses(
            license_number,
            license_type,
            license_status,
            dl_codes,
            issue_date,
            expiry_date,
            person_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param(
            "ssssssi",
            $licenseNumber,
            $licenseType,
            $licenseStatus,
            $dlCodes,
            $issueDate,
            $expiryDate,
            $personId
        );

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        return $this->conn->insert_id;
    }

    public function hydrate(array $row): License {
        $person = $this->personRepo->hydrate($row);

        $dlCodes = License::parseDLCodes($row['dl_codes']);
        $issueDate = new DateTime($row['issue_date']);
        $expiryDate = new DateTime($row['expiry_date']);

        return new License(
            $row["license_number"],
            LicenseTypeEnum::from($row["license_type"]),
            LicenseStatusEnum::from($row["license_status"]),
            $dlCodes,
            $issueDate,
            LicenseExpiryEnum::getInterval($issueDate, $expiryDate),
            $person,
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


    public function findByLicenseNumber(string $licenseNumber): ?License {
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
                JOIN people p ON l.person_id = p.person_id
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

    public function findById(int $licenseId): ?License {
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
                JOIN people p ON l.person_id = p.person_id
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