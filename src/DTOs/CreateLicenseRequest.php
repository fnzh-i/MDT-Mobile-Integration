<?php
namespace App\DTOs;

class CreateLicenseRequest {
    private string $firstName;
    private string $lastName;
    private ?string $middleName;
    private ?string $suffix;
    private string $dateOfBirth;
    private string $gender;
    private string $address;
    private string $nationality;
    private string $height;
    private string $weight;
    private string $eyeColor;
    private string $bloodType;

    private string $licenseNumber;
    private string $licenseType;
    private string $licenseStatus;
    private array $dlCodes;
    private string $issueDate;
    private int $expiryYears;

    public function __construct(array $data) {
        $this->firstName = $data['first_name'];
        $this->lastName = $data['last_name'];
        $this->middleName = $data['middle_name'] ?? null;
        $this->suffix = $data['suffix'] ?? null;
        $this->dateOfBirth = $data['date_of_birth'];
        $this->gender = $data['gender'];
        $this->address = $data['address'];
        $this->nationality = $data['nationality'];
        $this->height = $data['height'];
        $this->weight = $data['weight'];
        $this->eyeColor = $data['eye_color'];
        $this->bloodType = $data['blood_type'];

        $this->licenseNumber = $data['license_number'];
        $this->licenseType = $data['license_type'];
        $this->licenseStatus = $data['license_status'];
        $this->dlCodes = $data['dl_codes'] ?? [];
        $this->issueDate = $data['issue_date'];
        $this->expiryYears = (int)$data['expiry_years'];
    }

    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): string { return $this->lastName; }
    public function getMiddleName(): ?string { return $this->middleName; }
    public function getSuffix(): ?string { return $this->suffix; }
    public function getDateOfBirth(): string { return $this->dateOfBirth; }
    public function getGender(): string { return $this->gender; }
    public function getAddress(): string { return $this->address; }
    public function getNationality(): string { return $this->nationality; }
    public function getHeight(): string { return $this->height; }
    public function getWeight(): string { return $this->weight; }
    public function getEyeColor(): string { return $this->eyeColor; }
    public function getBloodType(): string { return $this->bloodType; }

    public function getLicenseNumber(): string { return $this->licenseNumber; }
    public function getLicenseType(): string { return $this->licenseType; }
    public function getLicenseStatus(): string { return $this->licenseStatus; }
    public function getDLCodes(): array { return $this->dlCodes; }
    public function getIssueDate(): string { return $this->issueDate; }
    public function getExpiryYears(): int { return $this->expiryYears; }
}
?>