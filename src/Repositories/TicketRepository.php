<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use App\Models\Ticket;
use App\Enums\TicketStatusEnum;
use App\Repositories\LicenseRepository;
use DateTime;

class TicketRepository {
    private mysqli $conn;
    private LicenseRepository $licenseRepo;

    public function __construct(mysqli $conn, LicenseRepository $licenseRepo) {
        $this->conn = $conn;
        $this->licenseRepo = $licenseRepo;
    }

    public function save(Ticket $ticket): int {
        $licenseId = $ticket->getLicense()->getId();
        $ref = $ticket->getRefNumber();
        $date = $ticket->getDateOfIncident();
        $place = $ticket->getPlaceOfIncident();
        $notes = $ticket->getNotes();
        $status = $ticket->getStatus();
        $total = $ticket->getTotalFine();

        $sql = "INSERT INTO tickets(
                    license_id,
                    ref_number,
                    date_of_incident,
                    place_of_incident,
                    notes,
                    status,
                    total_fine) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }
        
        $stmt->bind_param("iissssi",
                          $licenseId,
                          $ref,
                          $date,
                          $place,
                          $notes,
                          $status,
                          $total);
        
        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $ticketId = $this->conn->insert_id;

        $itemSql = "INSERT INTO ticket_items(
                        ticket_id,
                        violation_id,
                        name,
                        fine)
                    VALUES (?, ?, ?, ?)";

        $itemStmt = $this->conn->prepare($itemSql);

        if (!$itemStmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        foreach ($ticket->getItems() as $item) {
            $itemStmt->bind_param("iisi", 
                $ticketId, 
                $item['violation_id'], 
                $item['name'], 
                $item['fine']
            );

            if (!$itemStmt->execute()) {
                throw new RuntimeException("Execution Failed: {$itemStmt->error}");
            }
        }

        return $ticketId;
    }

    public function countPreviousOffenses(int $licenseId, int $violationId): int {
        $sql = "SELECT COUNT(*)
                FROM ticket_items ti 
                JOIN tickets t
                ON ti.ticket_id = t.ticket_id 
                WHERE t.license_id = ? AND ti.violation_id = ?";
        
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("ii", $licenseId, $violationId);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return (int)$result->fetch_row()[0];
    }

    public function getAllExistingRefNumbers(): array {
        $sql = "SELECT ref_number FROM tickets";

        $result = $this->conn->query($sql);
        
        $refNumbers = [];

        while ($row = $result->fetch_assoc()) {
            $refNumbers[] = (int)$row['ref_number'];
        }

        return $refNumbers;
    }

    public function getAllTickets(int $licenseId) {
        $sql = "SELECT t.*,
                    ti.name as v_name,
                    ti.fine as v_fine,
                    ti.violation_id, 
                    vl.is_penalty,
                    vl.base_fine,
                    vl.2nd_fine,
                    vl.3rd_fine
                FROM tickets t
                LEFT JOIN ticket_items ti
                ON t.ticket_id = ti.ticket_id
                LEFT JOIN violations_lookup vl
                ON ti.violation_id = vl.violation_id
                WHERE t.license_id = ?
                ORDER BY t.date_of_incident DESC, t.ticket_id DESC";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("i", $licenseId);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();

        $tickets = [];

        while ($row = $result->fetch_assoc()) {
            $tId = $row['ticket_id'];
            
            if (!isset($tickets[$tId])) {
                $tickets[$tId] = [
                    'id'               => $row['ticket_id'],
                    'refNumber'        => $row['ref_number'],
                    'dateOfIncident'   => (new DateTime($row['date_of_incident']))->format("F j, Y"),
                    'placeOfIncident'  => $row['place_of_incident'],
                    'notes'            => $row['notes'],
                    'status'           => $row['status'],
                    'totalFine'        => (int)$row['total_fine'],
                    'createdAt'        => (new DateTime($row["created_at"]))->format("F j, Y g:i A"),
                    'violations'        => []
                ];
            }

            $level = null;

            if ($row['is_penalty'] == 1) {
                if ($row['v_fine'] == $row['base_fine']) {
                    $level = "1st Offense";
                } elseif ($row['v_fine'] == $row['2nd_fine']) {
                    $level = "2nd Offense";
                } elseif ($row['v_fine'] == $row['3rd_fine']) {
                    $level = "3rd Offense";
                }
            }

            $tickets[$tId]['violations'][] = [
                'name'          => $row['v_name'],
                'fine'          => (int)$row['v_fine'],
                'offense_level' => $level
            ];
        }

        return array_values($tickets);
    }

    public function hydrate(array $row): Ticket {
        $ticket = new Ticket(
            $this->licenseRepo->hydrate($row),
            (int)$row['ref_number'],
            new DateTime($row['date_of_incident']),
            $row['place_of_incident'],
            $row['notes'],
            TicketStatusEnum::from($row['status']),
            (int)$row['ticket_id']
        );
        $ticket->setCreatedAt($row['created_at']);
        $ticket->setTotalFine((int)$row['total_fine']);
        return $ticket;
    }
}
?>