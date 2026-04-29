<?php
namespace App\Services;

use mysqli;
use Exception;
use InvalidArgumentException;
use App\DTOs\{CreateLicenseRequest, SearchLicenseResponse};
use App\Entities\{LicenseEntity, PersonEntity};
use App\Repositories\{PersonRepository, LicenseRepository, TicketRepository};
use DateTime;

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

    public function createLicense(CreateLicenseRequest $request): int {
        $this->conn->begin_transaction();

        try {
            $licenseNumber = $request->getLicenseNumber();
            
            if ($this->licenseRepo->existsByLicenseNumber($licenseNumber)) {
                throw new Exception("License number {$licenseNumber} already exists.");
            }

            $person = new PersonEntity(
                $request->getFirstName(),
                ($request->getMiddleName() === "") ? null: $request->getMiddleName(),
                $request->getLastName(),
                ($request->getSuffix() === "") ? null: $request->getSuffix(),
                $request->getDateOfBirth(),
                $request->getGender(),
                $request->getAddress(),
                $request->getNationality(),
                $request->getHeight(),
                $request->getWeight(),
                $request->getEyeColor(),
                $request->getBloodType()
            );

            $issueDate = $request->getIssueDate();
            $expiryOption = $request->getExpiryOption()->value;
            $expiryDate = (clone $issueDate)->modify("+$expiryOption years");

            $license = new LicenseEntity(
                $person,
                $licenseNumber,
                $request->getLicenseType(),
                $request->getLicenseStatus(),
                $request->getDLCodes(),
                $issueDate,
                $expiryDate
            );

            $personId = $this->personRepo->save($person);
            $person->setId($personId);

            $licenseId = $this->licenseRepo->save($license);

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

    public function generateLicenseNumber(): string {
        do {
            $newLicenseNumber = LicenseEntity::generateFormat("LNN-NN-NNNNNN");
            $alreadyExists = $this->licenseRepo->existsByLicenseNumber($newLicenseNumber);
            
        } while ($alreadyExists);

        return $newLicenseNumber;
    }
    public function updateLicense(int $id, array $data): void {
        $this->conn->begin_transaction();

        try {
            // Find the license to get the linked person_id
            $license = $this->licenseRepo->findById($id);
            if (!$license) throw new \Exception("License not found.");

            $personId = $license->getPerson()->getId();

            // Update License Table (License No. omitted in Repo SQL for readonly)
            $this->licenseRepo->update($id, $data);

            // Update Person Table (Address)
            if (isset($data['address'])) {
                $this->personRepo->updateAddress($personId, $data['address']);
            }

            $this->conn->commit();
        } catch (\Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function getLicenseById(int $id): ?LicenseEntity {
        return $this->licenseRepo->findById($id);
    }
    public function updateLicenseStatus(int $id, \App\Enums\LicenseStatusEnum $status): void {
        // Only updating the status field
        $updated = $this->licenseRepo->updateStatus($id, $status->value);

        if (!$updated) {
            throw new \Exception("Database update failed. The license might not exist.");
        }
    }
}
?>