<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use App\Entities\ViolationEntity;

class ViolationRepository {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function findById(int $violation_id): ?ViolationEntity {
        $sql = "SELECT violation_id,
                       name,
                       is_penalty,
                       base_fine,
                       fine_2nd,
                       fine_3rd 
                FROM violations_lookup 
                WHERE violation_id = ? 
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("i", $violation_id);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) return null;

        return $this->hydrate($row);
    }

    public function hydrate(array $row): ViolationEntity {
        return new ViolationEntity(
            (int)$row['violation_id'],
            $row['name'],
            (bool)$row['is_penalty'],
            (int)$row['base_fine'],
            (int)$row['fine_2nd'],
            (int)$row['fine_3rd']
        );
    }
}
?>