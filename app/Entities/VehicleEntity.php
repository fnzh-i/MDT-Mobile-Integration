<?php
namespace App\Entities;

use App\Enums\RegStatusEnum;
use DateTime;

class VehicleEntity {
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
    private ?LicenseEntity $license;
    private ?int $id;

    public function __construct(string $plateNumber,
                                string $mvFileNumber,
                                string $vin,
                                string $make,
                                string $model,
                                int $year,
                                string $color,
                                DateTime $issueDate,
                                DateTime $expiryDate,
                                RegStatusEnum $regStatus,
                                ?LicenseEntity $license = null,
                                ?int $id = null) {
        

        $this->plateNumber = $plateNumber;
        $this->mvFileNumber = $mvFileNumber;
        $this->vin = $vin;
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
        $this->color = $color;
        $this->issueDate = $issueDate;
        $this->expiryDate = $expiryDate;
        $this->regStatus = $regStatus;
        $this->license = $license;
        $this->id = $id;
    }

    public function getId(): ?int {return $this->id;}
    public function getLicense(): ?LicenseEntity {return $this->license;}
    public function getPlateNumber(): string {return $this->plateNumber;}
    public function getMVFileNumber(): string {return $this->mvFileNumber;}
    public function getVIN(): string {return $this->vin;}
    public function getMake(): string {return $this->make;}
    public function getModel(): string {return $this->model;}
    public function getYear(): int {return $this->year;}
    public function getColor(): string {return $this->color;}
    public function getIssueDate(): DateTime {return $this->issueDate;}
    public function getExpiryDate(): DateTime {return $this->expiryDate;}
    public function getRegStatus(): RegStatusEnum {return $this->regStatus;}
    
    public function setColor(string $color) {
        $this->color = $color;
    }
    public function setRegStatus(RegStatusEnum $regStatus) {
        $this->regStatus = $regStatus;
    }

    public static function createUniqueMVFileNum(array $existingMVFiles): string {
        $lookup = array_flip($existingMVFiles);

        do {
            $str = sprintf("%04d-%07d", mt_rand(0, 9999), mt_rand(0, 9999999));;
        } while (isset($lookup[$str]));

        return $str;
    }
}
?>