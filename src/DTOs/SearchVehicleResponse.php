<?php
namespace App\DTOs;

use App\Models\Vehicle;
use JsonSerializable;
use DateTime;

class SearchVehicleResponse implements JsonSerializable {
    private Vehicle $vehicle;

    public function __construct(Vehicle $vehicle) {
        $this->vehicle = $vehicle;
    }

    public function jsonSerialize(): array {
        $license = $this->vehicle->getLicense();
        $person  = $license->getPerson();

        return [
            "vehicle" => [
                "id"           => $this->vehicle->getId(),
                "plateNumber"  => $this->vehicle->getPlateNumber(),
                "mvFileNumber" => $this->vehicle->getMVFileNumber(),
                "make"         => $this->vehicle->getMake(),
                "model"        => $this->vehicle->getModel(),
                "year"         => $this->vehicle->getYear(),
                "color"        => $this->vehicle->getColor(),
                "regStatus"    => $this->vehicle->getRegStatus(),
                "issueDate"    => $this->vehicle->getIssueDate()->format("F j, Y"),
                "expiryDate"   => $this->vehicle->getExpiryDate()->format("F j, Y")
            ],
            "owner" => [
                "licenseNumber" => $license->getLicenseNumber(),
                "firstName"     => $person->getFirstName(),
                "middleName"    => $person->getMiddleName(),
                "lastName"      => $person->getLastName(),
                "suffix"        => $person->getSuffix(),
                "address"       => $person->getAddress()
            ]
        ];
    }

    // private function formatFullName($person): string {
    //     $mname = $person->getMiddleName();
    //     $mi = !empty($mname) ? " " . strtoupper($mname[0]) . "." : "";
    //     $suffix = !empty($person->getSuffix()) ? " " . $person->getSuffix() : "";
    //     return $person->getFirstName() . $mi . " " . $person->getLastName() . $suffix;
    // }
}