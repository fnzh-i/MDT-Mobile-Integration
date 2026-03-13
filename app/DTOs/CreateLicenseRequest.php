<?php
namespace App\DTOs;

use App\Enums\{LicenseTypeEnum, LicenseStatusEnum};
use DateTime;
use InvalidArgumentException;

class CreateLicenseRequest {
    private string $licenseNumber;
    private LicenseTypeEnum $licenseType;
    private LicenseStatusEnum $licenseStatus;
    private array $dlCodes = [];
    private DateTime $issueDate;
    private int $expiryOption;

    private string $firstName;
    private ?string $middleName;
    private string $lastName;
    private ?string $suffix;
    private DateTime $dateOfBirth;
    private string $gender;
    private string $address;
    private string $nationality;
    private string $height;
    private string $weight;
    private string $eyeColor;
    private string $bloodType;

    public function __construct(string $licenseNumber,
                                LicenseTypeEnum $licenseType,
                                LicenseStatusEnum $licenseStatus,
                                array $dlCodes,
                                DateTime $issueDate,
                                int $expiryOption,
                                string $firstName,
                                ?string $middleName,
                                string $lastName,
                                ?string $suffix,
                                DateTime $dateOfBirth,
                                string $gender,
                                string $address,
                                string $nationality,
                                string $height,
                                string $weight,
                                string $eyeColor,
                                string $bloodType) {

        $licenseNumber = trim($licenseNumber);
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $gender = trim($gender);
        $address = trim($address);
        $nationality = trim($nationality);
        $height = trim($height);
        $weight = trim($weight);
        $eyeColor = trim($eyeColor);
        $bloodType = trim($bloodType);

        if (empty($firstName)) {
            throw new InvalidArgumentException("First name required.");
        }
        if (empty($lastName)) {
            throw new InvalidArgumentException("Last name required.");
        }
        if (empty($gender)) {
            throw new InvalidArgumentException("Gender required.");
        }
        if (empty($address)) {
            throw new InvalidArgumentException("Address required.");
        }
        if (empty($nationality)) {
            throw new InvalidArgumentException("Nationality required.");
        }
        if (empty($height)) {
            throw new InvalidArgumentException("Height required.");
        }
        if (empty($weight)) {
            throw new InvalidArgumentException("Weight required.");
        }
        if (empty($eyeColor)) {
            throw new InvalidArgumentException("Eye color required.");
        }
        if (empty($bloodType)) {
            throw new InvalidArgumentException("Blood type required.");
        }

        $this->licenseNumber = $licenseNumber;
        $this->licenseType = $licenseType;
        $this->licenseStatus = $licenseStatus;
        $this->dlCodes = $dlCodes;
        $this->issueDate = $issueDate;
        $this->expiryOption = $expiryOption;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->suffix = $suffix;
        $this->dateOfBirth = $dateOfBirth;
        $this->gender = $gender;
        $this->address = $address;
        $this->nationality = $nationality;
        $this->height = $height;
        $this->weight = $weight;
        $this->eyeColor = $eyeColor;
        $this->bloodType = $bloodType;
    }

    public function getLicenseNumber(): string { return $this->licenseNumber; }
    public function getLicenseType(): LicenseTypeEnum { return $this->licenseType; }
    public function getLicenseStatus(): LicenseStatusEnum { return $this->licenseStatus; }
    public function getDLCodes(): array { return $this->dlCodes; }
    public function getIssueDate(): DateTime { return $this->issueDate; }
    public function getExpiryOption(): int { return $this->expiryOption; }

    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getMiddleName(): ?string { return $this->middleName; }
    public function getSuffix(): ?string { return $this->suffix; }
    public function getDateOfBirth(): DateTime { return $this->dateOfBirth; }
    public function getGender(): string { return $this->gender; }
    public function getAddress(): string { return $this->address; }
    public function getNationality(): string { return $this->nationality; }
    public function getHeight(): string { return $this->height; }
    public function getWeight(): string { return $this->weight; }
    public function getEyeColor(): string { return $this->eyeColor; }
    public function getBloodType(): string { return $this->bloodType; }
}
?>