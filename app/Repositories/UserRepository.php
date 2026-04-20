<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use App\Entities\UserEntity;
use App\Enums\UserRolesEnum;
use App\Entities\PersonEntity;

class UserRepository{
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function save(UserEntity $user): int {
        $lto_client_id = $user->getClientNumber();
        $first_name = $user->getFirstName();
        $middle_name = $user->getMiddleName();
        $last_name = $user->getLastName();
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $role = $user->getRole()->value;

        $sql = "INSERT INTO users(
            lto_client_id,
            role,
            first_name,
            middle_name,
            last_name,
            username,
            email,
            password,
            created_at, 
            updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param(
            "ssssssss",
            $lto_client_id,
            $role,
            $first_name,
            $middle_name,
            $last_name,
            $username,
            $email,
            $password
        );

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        return $this->conn->insert_id;
    }

    public function hydrate(array $row): UserEntity {
        return new UserEntity(
            $row["client_number"],
            $row["first_name"],
            $row["middle_name"] ?? null,
            $row["last_name"],
            $row["username"],
            $row["email"],
            $row["password"],
            UserRolesEnum::from($row["role"]),
            (int)$row["user_id"]
        );
    }

    public function findByUsername(string $username): ?UserEntity {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $username);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) return null;

        return $this->hydrate($row);
    }

    public function existsByClientNumber(string $client_number): bool {
        $sql = "SELECT 1 FROM users WHERE lto_client_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $client_number);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    public function existsByUsername(string $username): bool {
        $sql = "SELECT 1 FROM users WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $username);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function updatePassword(string $username, string $hashedPassword): bool {
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("ss", $hashedPassword, $username);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        return $stmt->affected_rows > 0;
    }

    public function searchByUsernameOrEmail(string $query): ?UserEntity {
        $sql = "SELECT user_id as user_id, lto_client_id as client_number, first_name, middle_name, last_name, username, email, password, role FROM users WHERE username = ? OR email = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("ss", $query, $query);

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