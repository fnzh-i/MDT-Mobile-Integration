<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use App\Entities\PersonEntity;
use DateTime;

class PersonRepository {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function save(PersonEntity $person): int {
        $firstName = $person->getFirstName();
        $lastName = $person->getLastName();
        $middleName = $person->getMiddleName();
        $suffix = $person->getSuffix();
        $dateOfBirth = $person->getDateOfBirth()->format("Y-m-d");
        $gender = $person->getGender();
        $address = $person->getAddress();
        $nationality = $person->getNationality();
        $height = $person->getHeight();
        $weight = $person->getWeight();
        $eyeColor = $person->getEyeColor();
        $bloodType = $person->getBloodType();

        $sql = "INSERT INTO persons(
            first_name,
            last_name,
            middle_name,
            suffix,
            date_of_birth,
            gender,
            address,
            nationality,
            height,
            weight,
            eye_color,
            blood_type,
            created_at,
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param(
            "ssssssssssss",
            $firstName,
            $lastName,
            $middleName,
            $suffix,
            $dateOfBirth,
            $gender,
            $address,
            $nationality,
            $height,
            $weight,
            $eyeColor,
            $bloodType
        );

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        return $this->conn->insert_id;
    }

    public function hydrate(array $row): PersonEntity {
      return new PersonEntity(
        $row["first_name"] ?? 'Unknown',    
        $row["middle_name"] ?? null,        
        $row["last_name"] ?? 'Unknown',     
        $row["suffix"] ?? null,             
        new DateTime($row["date_of_birth"] ?? 'now'), 
        $row["gender"] ?? 'Unknown',        
        $row["address"] ?? 'Unknown',       
        $row["nationality"] ?? 'Unknown',   
        $row["height"] ?? '0',              
        $row["weight"] ?? '0',              
        $row["eye_color"] ?? 'Unknown',     
        $row["blood_type"] ?? 'Unknown',    
        (int)$row["person_id"]              
      );
    }

    // public function findById(int $id): ?Person {
    //     $sql = "SELECT * FROM persons WHERE person_id = ?";
    //     $stmt = $this->conn->prepare($sql);
        
    //     if (!$stmt) {
    //         throw new RuntimeException("Prepare Failed: {$this->conn->error}");
    //     }

    public function findById(int $id): ?PersonEntity {
        $sql = "SELECT * FROM persons WHERE person_id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("i", $id);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return (is_array($row)) ? $this->hydrate($row) : null;
    }

    public function findByName(string $firstName, string $lastName, ?string $middleName = null): ?PersonEntity {
        if ($middleName) {
            $sql = "SELECT * FROM persons WHERE first_name = ? AND last_name = ? AND middle_name = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $firstName, $lastName, $middleName);
        } else {
            $sql = "SELECT * FROM persons WHERE first_name = ? AND last_name = ? LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $firstName, $lastName);
        }

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return ($row && is_array($row)) ? $this->hydrate($row) : null;
    }
}
?>