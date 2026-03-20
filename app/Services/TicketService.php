<?php
namespace App\Services;

use mysqli;
use Exception;
use App\DTOs\CreateTicketRequest;
use App\Entities\TicketEntity;
use App\Repositories\{LicenseRepository, TicketRepository, ViolationRepository};


class TicketService {
    private mysqli $conn;
    private LicenseRepository $licenseRepo;
    private TicketRepository $ticketRepo;
    private ViolationRepository $violationRepo;

    public function __construct(mysqli $conn,
                                LicenseRepository $licenseRepo,
                                TicketRepository $ticketRepo,
                                ViolationRepository $violationRepo) {

        $this->conn = $conn;
        $this->licenseRepo = $licenseRepo;
        $this->ticketRepo = $ticketRepo;
        $this->violationRepo = $violationRepo;
    }


    public function createTicket(CreateTicketRequest $request): int {
        $this->conn->begin_transaction();

        try {
            $licenseNumber = $request->getLicenseNumber();
            $license = $this->licenseRepo->findByLicenseNumber($licenseNumber);
            
            if ($license === null) {
                throw new Exception("License Number {$licenseNumber} not found.");
            }

            do {
                $uniqueRefNumber = TicketEntity::generateRandomRef();
                $alreadyExists = $this->ticketRepo->existsByRefNumber($uniqueRefNumber);
            } while ($alreadyExists);

            $ticket = new TicketEntity(
                license: $license,
                refNumber: $uniqueRefNumber,
                dateOfIncident: $request->getDateOfIncident(),
                placeOfIncident: $request->getPlaceOfIncident(),
                notes: $request->getNotes(),
                proofImage: $request->getProofImage()
            );

            $violationIds = $request->getViolationIds();

            foreach($violationIds as $vId) {
                $violation = $this->violationRepo->findById($vId);

                if ($violation === null) {
                    throw new Exception("Violation with ID {$vId} not found.");
                }

                $fine = $violation->getBaseFine();
                $level = null;

                if ($violation->isPenalty()) {
                    $offenseCount = $this->ticketRepo->countPreviousOffenses($license->getId(), $vId);

                    if ($offenseCount === 0) {
                        $level = "1st Offense";
                    } elseif ($offenseCount === 1) {
                        $fine = $violation->getSecondFine();
                        $level = "2nd Offense";
                    } else {
                        $fine = $violation->getThirdFine();
                        $level = "3rd Offense";
                    }
                }

                $ticket->addViolationItem(
                    $violation->getId(),
                    $violation->getName(),
                    $fine,
                    $level
                );
            }

            $ticketId = $this->ticketRepo->save($ticket);

            $this->conn->commit();

            return $ticketId;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
}
?>