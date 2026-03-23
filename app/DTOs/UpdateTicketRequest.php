<?php
class UpdateTicketRequest {
    private int $ticketId;
    private array $violationIds = [];
    private string $placeOfIncident;
    private ?string $notes;

    public function __construct(int $ticketId,
                                string $placeOfIncident,
                                ?string $notes,
                                array $violationIds) {
                                    
        if ($ticketId <= 0) throw new InvalidArgumentException("Invalid Ticket ID.");
        if (empty($violationIds)) throw new InvalidArgumentException("At least one violation required.");
        
        $this->ticketId = $ticketId;
        $this->violationIds = $violationIds;
        $this->placeOfIncident = $placeOfIncident;
        $this->notes = $notes;
    }

    public function getTicketId(): int { return $this->ticketId; }
    public function getViolationIds(): array { return $this->violationIds; }
    public function getPlaceOfIncident(): string { return $this->placeOfIncident; }
    public function getNotes(): ?string { return $this->notes; }
}
?>