<?php
namespace App\DTOs;
use DateTime;

class CreateTicketRequest {
    private int $licenseId;
    private array $violationIds;
    private DateTime $dateOfIncident;
    private string $placeOfIncident;
    private string $notes;

    public function __construct(int $licenseId,
                                array $violationIds,
                                DateTime $dateOfIncident,
                                string $placeOfIncident,
                                string $notes) {

        $this->licenseId = $licenseId;
        $this->violationIds = $violationIds;
        $this->dateOfIncident = $dateOfIncident;
        $this->placeOfIncident = $placeOfIncident;
        $this->notes = $notes;
    }

    public function getLicenseId(): int {return $this->licenseId;}
    public function getViolationIds(): array {return $this->violationIds;}
    public function getDateOfIncident(): DateTime {return $this->dateOfIncident;}
    public function getPlaceOfIncident(): string {return $this->placeOfIncident;}
    public function getNotes(): string {return $this->notes;}
}
?>