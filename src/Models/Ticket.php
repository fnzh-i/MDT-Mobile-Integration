<?php
namespace App\Models;

use App\Enums\TicketStatusEnum;
use DateTime;

class Ticket {
    private License $license;
    private int $refNumber;
    private DateTime $dateOfIncident;
    private string $placeOfIncident;
    private array $items = [];
    private ?string $notes;
    private TicketStatusEnum $status;
    private ?int $id;
    private int $totalFine = 0;
    private ?string $createdAt = null;


    public function __construct(License $license,
                                int $refNumber,
                                DateTime $dateOfIncident,
                                string $placeOfIncident,
                                ?string $notes,
                                TicketStatusEnum $status = TicketStatusEnum::Unsettled,
                                ?int $id = null) {

        $this->license = $license;
        $this->refNumber = $refNumber;
        $this->dateOfIncident = $dateOfIncident;
        $this->placeOfIncident = $placeOfIncident;
        $this->notes = $notes;
        $this->status = $status;
        $this->id = $id;
    }

    public function getRefNumber(): int {return $this->refNumber;}
    public function getDateOfIncident(): string {return $this->dateOfIncident->format("Y-m-d");}
    public function getPlaceOfIncident(): string {return $this->placeOfIncident;}
    public function getNotes(): ?string {return $this->notes;}
    public function getStatus(): string {return $this->status->value;}
    public function getItems(): array {return $this->items;}
    public function getLicense(): License {return $this->license;}
    public function getTotalFine(): int {return $this->totalFine;}
    public function getId(): ?int {return $this->id;}
    public function getCreatedAt(): ?string {return $this->createdAt;}

    public function setStatus(TicketStatusEnum $status) {
        $this->status = $status;
    }
    public function setLicense(License $license) {
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

    public static function createUniqueRefNum(array $existingRefNums): int {
        $lookup = array_flip($existingRefNums);

        do {
            $num = random_int(1000000000, 9999999999);
        } while (isset($lookup[$num]));

        return $num;
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