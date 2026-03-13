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
            blood_type
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
            $row["first_name"],
            $row["middle_name"] ?? null,
            $row["last_name"],
            $row["suffix"] ?? null,
            new DateTime($row["date_of_birth"]),
            $row["gender"],
            $row["address"],
            $row["nationality"],
            $row["height"],
            $row["weight"],
            $row["eye_color"],
            $row["blood_type"],
            (int)$row["person_id"]
        );
    }

    // public function findById(int $id): ?Person {
    //     $sql = "SELECT * FROM persons WHERE person_id = ?";
    //     $stmt = $this->conn->prepare($sql);
        
    //     if (!$stmt) {
    //         throw new RuntimeException("Prepare Failed: {$this->conn->error}");
    //     }

    //     $stmt->bind_param("i", $id);

    //     if (!$stmt->execute()) {
    //         throw new RuntimeException("Execution Failed: {$stmt->error}");
    //     }

    //     $row = $stmt->get_result()->fetch_assoc();

    //     if (!$row) return null;

    //     return $this->hydrate($row);
    // }
}
?>