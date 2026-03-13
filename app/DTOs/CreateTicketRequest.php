<?php
namespace App\DTOs;
use DateTime;
use InvalidArgumentException;

class CreateTicketRequest {
    private int $licenseId;
    private array $violationIds = [];
    private DateTime $dateOfIncident;
    private string $placeOfIncident;
    private ?string $notes;

    public function __construct(int $licenseId,
                                array $violationIds,
                                DateTime $dateOfIncident,
                                string $placeOfIncident,
                                ?string $notes) {


        if ($licenseId <= 0) {
            throw new InvalidArgumentException ("License ID required.");
        }
        if (empty($violationIds)) {
            throw new InvalidArgumentException("At least one violation required.");
        }
        if (empty(trim($placeOfIncident))) {
            throw new InvalidArgumentException("Place of incident required.");
        }

        $this->licenseId = $licenseId;
        $this->violationIds = $violationIds;
        $this->dateOfIncident = $dateOfIncident;
        $this->placeOfIncident = $placeOfIncident;
        $this->notes = ($notes === "" || $notes === null) ? null : trim($notes);
    }

    public function getLicenseId(): int {return $this->licenseId;}
    public function getViolationIds(): array {return $this->violationIds;}
    public function getDateOfIncident(): DateTime {return $this->dateOfIncident;}
    public function getPlaceOfIncident(): string {return $this->placeOfIncident;}
    public function getNotes(): ?string {return $this->notes;}
}
?>