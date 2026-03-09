<?php
namespace App\Services;

use mysqli;
use Exception;
use InvalidArgumentException;
use App\DTOs\SearchLicenseResponse;
use App\Models\License;
use App\Repositories\{PersonRepository, LicenseRepository, TicketRepository};

class LicenseService {
    private mysqli $conn;
    private PersonRepository $personRepo;
    private LicenseRepository $licenseRepo;
    private TicketRepository $ticketRepo;

    public function __construct(mysqli $conn,
                                PersonRepository $personRepo,
                                LicenseRepository $licenseRepo,
                                TicketRepository $ticketRepo) {
        $this->conn = $conn;
        $this->personRepo = $personRepo;
        $this->licenseRepo = $licenseRepo;
        $this->ticketRepo = $ticketRepo;
    }

    public function createLicense(License $license): int {
        $this->conn->begin_transaction();

        try {
            $licenseNumber = $license->getLicenseNumber();
            
            if ($this->licenseRepo->existsByLicenseNumber($licenseNumber)) {
                throw new Exception("License number {$licenseNumber} already exists.");
            }

            $person = $license->getPerson();
            $personId = $this->personRepo->save($person);

            $licenseId = $this->licenseRepo->save($license, $personId);

            $this->conn->commit();

            return $licenseId;

        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function searchLicense(string $licenseNumber): SearchLicenseResponse {
        $licenseNumber = trim($licenseNumber);

        if (empty($licenseNumber)) {
            throw new InvalidArgumentException("Please enter the license number.");
        }

        $license = $this->licenseRepo->findByLicenseNumber($licenseNumber);

        if ($license === null) {
            throw new Exception("License with license number {$licenseNumber} not found.");
        }

        $licenseId = $license->getId();
        $tickets = $this->ticketRepo->getAllTickets($licenseId);

        return new SearchLicenseResponse($license, $tickets);
    }
}
?>