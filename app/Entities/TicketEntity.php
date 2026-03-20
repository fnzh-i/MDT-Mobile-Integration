<?php
namespace App\Entities;

use App\Enums\TicketStatusEnum;
use DateTime;

class TicketEntity {
    private LicenseEntity $license;
    private int $refNumber;
    private DateTime $dateOfIncident;
    private string $placeOfIncident;
    private array $items = [];
    private ?string $notes;
    private TicketStatusEnum $status;
    private ?int $id;
    private int $totalFine = 0;
    private ?string $createdAt = null;
    private ?string $proofImage;


    public function __construct(LicenseEntity $license,
                                int $refNumber,
                                DateTime $dateOfIncident,
                                string $placeOfIncident,
                                ?string $notes,
                                TicketStatusEnum $status = TicketStatusEnum::Unsettled,
                                ?int $id = null,
                                ?string $proofImage = null) {

        $this->license = $license;
        $this->refNumber = $refNumber;
        $this->dateOfIncident = $dateOfIncident;
        $this->placeOfIncident = $placeOfIncident;
        $this->notes = $notes;
        $this->status = $status;
        $this->id = $id;
        $this->proofImage = $proofImage;
    }

    public function getRefNumber(): int {return $this->refNumber;}
    public function getDateOfIncident(): string {return $this->dateOfIncident->format("Y-m-d");}
    public function getPlaceOfIncident(): string {return $this->placeOfIncident;}
    public function getNotes(): ?string {return $this->notes;}
    public function getStatus(): string {return $this->status->value;}
    public function getItems(): array {return $this->items;}
    public function getLicense(): LicenseEntity {return $this->license;}
    public function getTotalFine(): int {return $this->totalFine;}
    public function getId(): ?int {return $this->id;}
    public function getCreatedAt(): ?string {return $this->createdAt;}
    public function getProofImage(): ?string {return $this->proofImage;}

    public function setStatus(TicketStatusEnum $status) {
        $this->status = $status;
    }
    public function setLicense(LicenseEntity $license) {
        $this->license = $license;
    }
    public function setRefNumber(int $refNumber): void {
        $this->refNumber = $refNumber;
    }
    public function setTotalFine(int $totalFine): void {
        $this->totalFine = $totalFine;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public static function generateRandomRef(): int {
        return random_int(1000000000, 9999999999);
    }

    public function addViolationItem(int $violationId,
                                     string $name,
                                     int $fine,
                                     ?string $offenseLevel): void {
        $this->items[] = [
            'violation_id' => $violationId,
            'name' => $name,
            'fine' => $fine,
            'offenseLevel' => $offenseLevel
        ];
        $this->totalFine += $fine;
    }
}
?>