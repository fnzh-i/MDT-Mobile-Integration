<?php
namespace App\DTOs;

use App\Models\License;
use App\Models\Ticket;
use JsonSerializable;
use DateTime;

class SearchLicenseResponse implements JsonSerializable {
    private License $license;
    private array $tickets;

    public function __construct(License $license, array $tickets = []) {
        $this->license = $license;
        $this->tickets = $tickets;
    }

    public function jsonSerialize(): array {
        $person = $this->license->getPerson();


        return [
            "license" => [
                "id"            => $this->license->getId(),
                "licenseNumber" => $this->license->getLicenseNumber(),
                "type"          => $this->license->getLicenseType(),
                "status"        => $this->license->getLicenseStatus(),
                "dlCodes"       => $this->license->getDLCodesAsString(),
                "issueDate"     => $this->license->getIssueDate()->format("F j, Y"),
                "expiryDate"    => $this->license->getExpiryDate()->format("F j, Y")
            ],
            "person" => [
                "firstName"   => $person->getFirstName(),
                "lastName"    => $person->getLastName(),
                "middleName"  => $person->getMiddleName(),
                "suffix"      => $person->getSuffix(),
                "dateOfBirth" => $person->getDateOfBirth()->format("F j, Y"),
                "gender"      => $person->getGender(),
                "address"     => $person->getAddress(),
                "nationality" => $person->getNationality(),
                "height"      => $person->getHeight(),
                "weight"      => $person->getWeight(),
                "eyeColor"    => $person->getEyeColor(),
                "bloodType"   => $person->getBloodType()
            ],
            "tickets" => $this->tickets
        ];
    }

    // private function formatFullName($person): string {
    //     $mname = $person->getMiddleName();
    //     $mi = !empty($mname) ? " " . strtoupper($mname[0]) . "." : "";
    //     $suffix = !empty($person->getSuffix()) ? " " . $person->getSuffix() : "";
    //     return $person->getFirstName() . $mi . " " . $person->getLastName() . $suffix;
    // }
}
?>