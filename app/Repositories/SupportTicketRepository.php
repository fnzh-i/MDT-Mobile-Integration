<?php

namespace App\Repositories;

use mysqli;

class SupportTicketRepository
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function save(int $userId, string $category, string $message): int
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO support_tickets (user_id, category, message, status, created_at, updated_at) 
             VALUES (?, ?, ?, 'Open', NOW(), NOW())"
        );

        if (!$stmt) {
            throw new \Exception("Prepare statement failed: " . $this->conn->error);
        }

        $stmt->bind_param("iss", $userId, $category, $message);

        if (!$stmt->execute()) {
            throw new \Exception("Execution failed: " . $stmt->error);
        }

        $ticketId = $this->conn->insert_id;
        $stmt->close();

        return $ticketId;
    }

    /**
     * Get all support tickets
     */
    public function getAll(): array
    {
        $query = "
            SELECT 
                st.id,
                st.user_id,
                st.category,
                st.message,
                st.status,
                st.admin_response,
                st.created_at,
                st.updated_at,
                u.email,
                u.username,
                CONCAT(u.first_name, ' ', u.last_name) as user_name
            FROM support_tickets st
            LEFT JOIN users u ON st.user_id = u.id
            ORDER BY st.created_at DESC
        ";

        $result = $this->conn->query($query);

        if (!$result) {
            throw new \Exception("Query failed: " . $this->conn->error);
        }

        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }

        return $tickets;
    }

    /**
     * Get a single ticket by ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM support_tickets WHERE id = ?"
        );

        if (!$stmt) {
            throw new \Exception("Prepare statement failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row;
    }

    /**
     * Update ticket status
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE support_tickets SET status = ?, updated_at = NOW() WHERE id = ?"
        );

        if (!$stmt) {
            throw new \Exception("Prepare statement failed: " . $this->conn->error);
        }

        $stmt->bind_param("si", $status, $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Add admin response to a ticket
     */
    public function addResponse(int $id, string $response): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE support_tickets SET admin_response = ?, status = 'Resolved', updated_at = NOW() WHERE id = ?"
        );

        if (!$stmt) {
            throw new \Exception("Prepare statement failed: " . $this->conn->error);
        }

        $stmt->bind_param("si", $response, $id);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * Count open tickets
     */
    public function countOpen(): int
    {
        $result = $this->conn->query(
            "SELECT COUNT(*) as count FROM support_tickets WHERE status = 'Open'"
        );

        if (!$result) {
            throw new \Exception("Query failed: " . $this->conn->error);
        }

        $row = $result->fetch_assoc();
        return $row['count'] ?? 0;
    }
}
