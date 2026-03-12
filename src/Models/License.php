<?php
namespace App\Models;

use App\Enums\{LicenseTypeEnum, LicenseStatusEnum};
use DateTime;

class License {
    private Person $person;
    private string $licenseNumber;
    private LicenseTypeEnum $licenseType;
    private LicenseStatusEnum $licenseStatus;
    private array $dlCodes = [];
    private DateTime $issueDate;
    private DateTime $expiryDate;
    private ?int $id;

    public function __construct(Person $person,
                                string $licenseNumber,
                                LicenseTypeEnum $licenseType,
                                LicenseStatusEnum $licenseStatus,
                                array $dlCodes,
                                DateTime $issueDate,
                                DateTime $expiryDate,
                                ?int $id = null) {

        $this->person = $person;
        $this->licenseNumber = $licenseNumber;
        $this->licenseType = $licenseType;
        $this->licenseStatus = $licenseStatus;
        $this->dlCodes = $dlCodes;
        $this->issueDate = $issueDate;
        $this->expiryDate = $expiryDate;
        $this->id = $id;
    }

    public function getId(): ?int {return $this->id;}
    public function getPerson(): Person {return $this->person;}
    public function getLicenseNumber(): string {return $this->licenseNumber;}
    public function getLicenseType(): LicenseTypeEnum {return $this->licenseType;}
    public function getLicenseStatus(): LicenseStatusEnum {return $this->licenseStatus;}
    public function getDLCodes(): array {return $this->dlCodes;}
    public function getDLCodesAsString(): string {
        return implode(", ", $this->dlCodes);
    }
    public static function parseDLCodes(string $dlCodesString): array {
        if (empty($dlCodesString)) {
            return [];
        }
        return array_map('trim', explode(',', $dlCodesString));
    }
    public function getIssueDate(): DateTime {return $this->issueDate;}
    public function getExpiryDate(): DateTime {return $this->expiryDate;}

    public function setLicenseType(LicenseTypeEnum $licenseType) {
        $this->licenseType = $licenseType;
    }
    public function setLicenseStatus(LicenseStatusEnum $licenseStatus) {
        $this->licenseStatus = $licenseStatus;
    }
    public function setPerson(Person $person) {
        $this->person = $person;
    }
}
?>