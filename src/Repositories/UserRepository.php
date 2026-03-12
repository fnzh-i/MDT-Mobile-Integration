<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use App\Models\User;
use App\Enums\UserRolesEnum;
use App\Models\Person;

class UserRepository{
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function save(User $user): int {
        $firstName = $user->getFirstName();
        $middleName = $user->getMiddleName();
        $lastName = $user->getLastName();
        $username = $user->getUsername();
        $password = $user->getPassword();
        $role = $user->getRole()->value;

        $sql = "INSERT INTO users(
            role,
            first_name,
            middle_name,
            last_name,
            username,
            password
        ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param(
            "ssssss",
            $role,
            $firstName,
            $middleName,
            $lastName,
            $username,
            $password
        );

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        return $this->conn->insert_id;
    }

    public function hydrate(array $row): User {
        return new User(
            $row["first_name"],
            $row["middle_name"] ?? null,
            $row["last_name"],
            $row["username"],
            $row["password"],
            UserRolesEnum::from($row["role"]),
            (int)$row["user_id"]
        );
    }

    public function findByUsername(string $username): ?User {
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
}
?>