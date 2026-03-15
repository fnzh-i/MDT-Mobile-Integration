<?php
namespace App\DTOs;

use App\Enums\RegExpiryEnum;
use App\Enums\RegStatusEnum;
use DateTime;
use InvalidArgumentException;
use KitLoong\MigrationsGenerator\Support\Regex;

class CreateVehicleRequest {
    private string $licenseNumber;
    private string $plateNumber;
    private string $mvFileNumber;
    private string $vin;
    private string $make;
    private string $model;
    private int $year;
    private string $color;
    private DateTime $issueDate;
    private RegExpiryEnum $expiryOption;
    private RegStatusEnum $regStatus;
    

    public function __construct(string $licenseNumber,
                                string $plateNumber,
                                string $mvFileNumber,
                                string $vin,
                                string $make,
                                string $model,
                                int $year,
                                string $color,
                                DateTime $issueDate,
                                RegExpiryEnum $expiryOption,
                                RegStatusEnum $regStatus) {


        $licenseNumber = trim($licenseNumber);
        $plateNumber = trim($plateNumber);
        $mvFileNumber = trim($mvFileNumber);
        $vin = trim($vin);
        $make = trim($make);
        $model = trim($model);
        $color = trim($color);

        if (empty($licenseNumber)) throw new InvalidArgumentException("License number required.");
        if (empty($plateNumber)) throw new InvalidArgumentException("Plate number required.");
        if (empty($mvFileNumber)) throw new InvalidArgumentException("MV file number required.");
        if (empty($vin)) throw new InvalidArgumentException("VIN required.");
        if (empty($make)) throw new InvalidArgumentException("Make required.");
        if (empty($model)) throw new InvalidArgumentException("Model required.");
        if (empty($color)) throw new InvalidArgumentException("Color required.");

        if ($issueDate > new DateTime()) {
            throw new InvalidArgumentException("Issue date cannot be in the future!");
        }


        $this->licenseNumber = strtoupper($licenseNumber);
        $this->plateNumber = strtoupper($plateNumber);
        $this->mvFileNumber = strtoupper($mvFileNumber);
        $this->vin = strtoupper($vin);
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
        $this->color = $color;
        $this->issueDate = $issueDate;
        $this->expiryOption = $expiryOption;
        $this->regStatus = $regStatus;
    }

    public function getPlateNumber(): string { return $this->plateNumber; }
    public function getMVFileNumber(): string { return $this->mvFileNumber; }
    public function getVIN(): string { return $this->vin; }
    public function getMake(): string { return $this->make; }
    public function getModel(): string { return $this->model; }
    public function getYear(): int { return $this->year; }
    public function getColor(): string { return $this->color; }
    public function getIssueDate(): DateTime { return $this->issueDate; }
    public function getExpiryOption(): RegExpiryEnum {return $this->expiryOption;}
    public function getRegStatus(): RegStatusEnum { return $this->regStatus; }
    public function getLicenseNumber(): string { return $this->licenseNumber; }
}
?>