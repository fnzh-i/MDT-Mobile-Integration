<?php
namespace App\DTOs;
use DateTime;
use InvalidArgumentException;

class CreateTicketRequest {
    private string $licenseNumber;
    private array $violationIds = [];
    private DateTime $dateOfIncident;
    private string $placeOfIncident;
    private ?string $notes;
    private ?string $proofImage;

    public function __construct(string $licenseNumber,
                                array $violationIds,
                                DateTime $dateOfIncident,
                                string $placeOfIncident,
                                ?string $notes,
                                ?string $proofImage = null) {


        if (empty(trim($licenseNumber))) {
            throw new InvalidArgumentException("License Number is required.");
        }
        if (empty($violationIds)) {
            throw new InvalidArgumentException("At least one violation required.");
        }
        if (empty(trim($placeOfIncident))) {
            throw new InvalidArgumentException("Place of incident required.");
        }

        $this->licenseNumber = $licenseNumber;
        $this->violationIds = $violationIds;
        $this->dateOfIncident = $dateOfIncident;
        $this->placeOfIncident = $placeOfIncident;
        $this->notes = ($notes === "" || $notes === null) ? null : trim($notes);
        $this->proofImage = $proofImage;
    }

    public function getLicenseNumber(): string {return $this->licenseNumber;}
    public function getViolationIds(): array {return $this->violationIds;}
    public function getDateOfIncident(): DateTime {return $this->dateOfIncident;}
    public function getPlaceOfIncident(): string {return $this->placeOfIncident;}
    public function getNotes(): ?string {return $this->notes;}
    public function getProofImage(): ?string {return $this->proofImage;}
}
?>