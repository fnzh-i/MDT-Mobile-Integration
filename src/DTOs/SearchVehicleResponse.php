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

    public function jsonSerialize(): mixed {
        $license = $this->vehicle->getLicense();
        $person  = $license->getPerson();

        return [
            "vehicle" => [
                "id"           => $this->vehicle->getId(),
                "plateNumber"  => $this->vehicle->getPlateNumber(),
                "make"         => $this->vehicle->getMake(),
                "model"        => $this->vehicle->getModel(),
                "year"         => $this->vehicle->getYear(),
                "color"        => $this->vehicle->getColor(),
                "regStatus"    => $this->vehicle->getRegStatus(),
                "issueDate"    => (new DateTime($this->vehicle->getIssueDate()))->format("F j, Y"),
                "expiryDate"   => (new DateTime($this->vehicle->getExpiryDate()))->format("F j, Y")
            ],
            "person" => [
                "licenseNumber" => $license->getLicenseNumber(),
                "fullName"      => $this->formatFullName($person),
                "address"       => $person->getAddress()
            ]
        ];
    }

    private function formatFullName($person): string {
        $mname = $person->getMiddleName();
        $mi = !empty($mname) ? " " . strtoupper($mname[0]) . "." : "";
        $suffix = !empty($person->getSuffix()) ? " " . $person->getSuffix() : "";
        return $person->getFirstName() . $mi . " " . $person->getLastName() . $suffix;
    }
}