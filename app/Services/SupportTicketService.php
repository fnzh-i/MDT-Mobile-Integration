<?php

namespace App\Services;

use Exception;
use mysqli;
use App\Repositories\SupportTicketRepository;
use App\Entities\SupportTicketEntity;

class SupportTicketService
{
    private mysqli $conn;
    private SupportTicketRepository $repository;

    public function __construct(mysqli $conn, SupportTicketRepository $repository)
    {
        $this->conn = $conn;
        $this->repository = $repository;
    }

    /**
     * Create and save a new support ticket
     */
    public function createTicket(int $userId, string $category, string $message): int
    {
        // Validation
        if (empty($userId)) {
            throw new Exception("User ID is required.");
        }

        if (empty($category)) {
            throw new Exception("Category is required.");
        }

        if (strlen($message) < 10) {
            throw new Exception("Message must be at least 10 characters long.");
        }

        if (strlen($message) > 5000) {
            throw new Exception("Message cannot exceed 5000 characters.");
        }

        // Sanitize inputs
        $category = trim($category);
        $message = trim($message);

        // Save to database
        $ticketId = $this->repository->save($userId, $category, $message);

        return $ticketId;
    }

    /**
     * Get all support tickets with user information
     */
    public function getAllTickets(): array
    {
        $ticketsData = $this->repository->getAll();
        
        $tickets = [];
        foreach ($ticketsData as $data) {
            $tickets[] = $this->hydrate($data);
        }

        return $tickets;
    }

    /**
     * Get a single ticket by ID
     */
    public function getTicketById(int $id): ?SupportTicketEntity
    {
        $data = $this->repository->getById($id);
        
        if (!$data) {
            return null;
        }

        return $this->hydrate($data);
    }

    /**
     * Update ticket status
     */
    public function updateTicketStatus(int $id, string $status): bool
    {
        // Validate status
        $validStatuses = ['Open', 'In Progress', 'Resolved', 'Closed'];
        if (!in_array($status, $validStatuses)) {
            throw new Exception("Invalid status. Must be one of: " . implode(', ', $validStatuses));
        }

        return $this->repository->updateStatus($id, $status);
    }

    /**
     * Add admin response and mark as resolved
     */
    public function respondToTicket(int $id, string $response): bool
    {
        if (empty($response)) {
            throw new Exception("Response message is required.");
        }

        if (strlen($response) < 5) {
            throw new Exception("Response must be at least 5 characters long.");
        }

        if (strlen($response) > 5000) {
            throw new Exception("Response cannot exceed 5000 characters.");
        }

        $response = trim($response);

        return $this->repository->addResponse($id, $response);
    }

    /**
     * Get count of open tickets
     */
    public function getOpenTicketCount(): int
    {
        return $this->repository->countOpen();
    }

    /**
     * Convert database row to SupportTicketEntity
     */
    private function hydrate(array $data): SupportTicketEntity
    {
        return new SupportTicketEntity(
            (int)$data['id'] ?? 0,
            (int)$data['user_id'] ?? 0,
            $data['category'] ?? '',
            $data['message'] ?? '',
            $data['status'] ?? 'Open',
            $data['admin_response'] ?? null,
            $data['created_at'] ?? '',
            $data['updated_at'] ?? '',
            $data['email'] ?? null,
            $data['username'] ?? null,
            $data['user_name'] ?? null
        );
    }
}
