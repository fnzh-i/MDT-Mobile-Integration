<?php
namespace App\Models;

use App\Enums\RegStatusEnum;
use DateTime;

class Vehicle {
    private string $plateNumber;
    private string $mvFileNumber;
    private string $vin;
    private string $make;
    private string $model;
    private int $year;
    private string $color;
    private DateTime $issueDate;
    private DateTime $expiryDate;
    private RegStatusEnum $regStatus;
    private ?License $license;
    private ?int $id;

    public function __construct(string $plateNumber,
                                string $mvFileNumber,
                                string $vin,
                                string $make,
                                string $model,
                                int $year,
                                string $color,
                                DateTime $issueDate,
                                RegStatusEnum $regStatus,
                                ?License $license = null,
                                ?int $id = null) {
        

        $this->plateNumber = $plateNumber;
        $this->mvFileNumber = $mvFileNumber;
        $this->vin = $vin;
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
        $this->color = $color;
        $this->issueDate = $issueDate;
        $this->expiryDate = (clone $issueDate)->modify("+1 year");
        $this->regStatus = $regStatus;
        $this->license = $license;
        $this->id = $id;
    }

    public function getId(): int {return $this->id;}
    public function getLicense(): ?License {return $this->license;}
    public function getPlateNumber(): string {return $this->plateNumber;}
    public function getMVFileNumber(): string {return $this->mvFileNumber;}
    public function getVIN(): string {return $this->vin;}
    public function getMake(): string {return $this->make;}
    public function getModel(): string {return $this->model;}
    public function getYear(): int {return $this->year;}
    public function getColor(): string {return $this->color;}
    public function getIssueDate(): string {return $this->issueDate->format("Y-m-d");}
    public function getExpiryDate(): string {return $this->expiryDate->format("Y-m-d");}
    public function getRegStatus(): string {return $this->regStatus->value;}
    
    public function setColor(string $color) {
        $this->color = $color;
    }
    public function setRegStatus(RegStatusEnum $regStatus) {
        $this->regStatus = $regStatus;
    }
}
?>