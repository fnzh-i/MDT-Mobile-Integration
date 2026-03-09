<?php
namespace App\Models;

use App\Enums\LicenseTypeEnum;
use App\Enums\LicenseStatusEnum;
use App\Enums\LicenseExpiryEnum;
use App\Enums\DLCodesEnum;
use DateTime;

class License {
    private string $licenseNumber;
    private LicenseTypeEnum $licenseType;
    private LicenseStatusEnum $licenseStatus;
    private array $dlCodes;
    private DateTime $issueDate;
    private DateTime $expiryDate;
    private Person $person;
    private ?int $id;

    public function __construct(string $licenseNumber,
                                LicenseTypeEnum $licenseType,
                                LicenseStatusEnum $licenseStatus,
                                array $dlCodes,
                                DateTime $issueDate,
                                LicenseExpiryEnum $expiry,
                                Person $person,
                                ?int $id = null) {

        $this->licenseNumber = $licenseNumber;
        $this->licenseType = $licenseType;
        $this->licenseStatus = $licenseStatus;
        $this->dlCodes = $dlCodes;
        $this->issueDate = $issueDate;
        $this->expiryDate = (clone $issueDate)->modify("+{$expiry->value} years");
        $this->person = $person;
        $this->id = $id;
    }

    public function getId(): int {return $this->id;}
    public function getLicenseNumber(): string {return $this->licenseNumber;}
    public function getLicenseType(): string {return $this->licenseType->value;}
    public function getLicenseStatus(): string {return $this->licenseStatus->value;}
    public function getDLCodes(): array {return $this->dlCodes;}
    public function getDLCodesAsString(): string {
        return implode(", ", array_map(fn($code) => $code->value, $this->dlCodes));
    }
    public static function parseDLCodes(string $codesString): array {
        $codes = explode(", ", $codesString);
        return array_map(fn($code) => DLCodesEnum::from($code), $codes);
    }
    public function getIssueDate(): string {return $this->issueDate->format("Y-m-d");}
    public function getExpiryDate(): string {return $this->expiryDate->format("Y-m-d");}
    public function getPerson(): Person {return $this->person;}

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