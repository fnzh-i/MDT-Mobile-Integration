<?php

namespace App\Entities;

class SupportTicketEntity
{
    private int $id;
    private int $userId;
    private string $category;
    private string $message;
    private string $status;
    private ?string $adminResponse;
    private string $createdAt;
    private string $updatedAt;
    private ?string $userEmail;
    private ?string $username;
    private ?string $userName;

    public function __construct(
        int $id,
        int $userId,
        string $category,
        string $message,
        string $status,
        ?string $adminResponse = null,
        string $createdAt = '',
        string $updatedAt = '',
        ?string $userEmail = null,
        ?string $username = null,
        ?string $userName = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->category = $category;
        $this->message = $message;
        $this->status = $status;
        $this->adminResponse = $adminResponse;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->userEmail = $userEmail;
        $this->username = $username;
        $this->userName = $userName;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getCategory(): string { return $this->category; }
    public function getMessage(): string { return $this->message; }
    public function getStatus(): string { return $this->status; }
    public function getAdminResponse(): ?string { return $this->adminResponse; }
    public function getCreatedAt(): string { return $this->createdAt; }
    public function getUpdatedAt(): string { return $this->updatedAt; }
    public function getUserEmail(): ?string { return $this->userEmail; }
    public function getUsername(): ?string { return $this->username; }
    public function getUserName(): ?string { return $this->userName; }
}
