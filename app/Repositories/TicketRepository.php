<?php
namespace App\Repositories;

use mysqli;
use RuntimeException;
use App\Entities\TicketEntity;
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

    public function save(TicketEntity $ticket): int {
        $licenseId = $ticket->getLicense()->getId();
        $ref = $ticket->getRefNumber();
        $date = $ticket->getDateOfIncident();
        $place = $ticket->getPlaceOfIncident();
        $notes = $ticket->getNotes();
        $status = $ticket->getStatus();
        $total = $ticket->getTotalFine();
        $proofImage = $ticket->getProofImage();

        $sql = "INSERT INTO tickets(
                    license_id,
                    ref_number,
                    date_of_incident,
                    place_of_incident,
                    notes,
                    status,
                    total_fine, 
                    created_at,
                    updated_at,
                    proof_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)";
        
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $null = null;
        
        $stmt->bind_param("iissssib",
                          $licenseId,
                          $ref,
                          $date,
                          $place,
                          $notes,
                          $status,
                          $total,
                          $null);
        

        if ($proofImage !== null) {
            $stmt->send_long_data(7, $proofImage);
        }

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

    public function countPreviousOffenses(int $licenseId, int $violationId, ?int $excludeTicketId = null): int {
        $sql = "SELECT COUNT(*)
                FROM ticket_items ti 
                JOIN tickets t
                ON ti.ticket_id = t.ticket_id 
                WHERE t.license_id = ? AND ti.violation_id = ?";

        // If we are updating, don't count the ticket we're currently in
        if ($excludeTicketId !== null) {
            $sql .= " AND t.ticket_id != ?";
        }
        
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        if ($excludeTicketId !== null) {
            $stmt->bind_param("iii", $licenseId, $violationId, $excludeTicketId);
        } else {
            $stmt->bind_param("ii", $licenseId, $violationId);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return (int)$result->fetch_row()[0];
    }

    public function existsByRefNumber(int $refNumber): bool {
        $sql = "SELECT 1 FROM tickets WHERE ref_number = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("i", $refNumber);
        
        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function getAllTickets(int $licenseId) {
        $sql = "SELECT t.*,
                    ti.name as v_name,
                    ti.fine as v_fine,
                    ti.violation_id, 
                    vl.is_penalty,
                    vl.base_fine,
                    vl.fine_2nd,
                    vl.fine_3rd
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
                    'proof_image'      => $row['proof_image'],
                    'createdAt'        => (new DateTime($row["created_at"]))->format("F j, Y g:i A"),
                    'updatedAt'        => (new DateTime($row["updated_at"]))->format("F j, Y g:i A"),
                    'violations'        => []
                ];
            }

            $level = null;

            if ($row['is_penalty'] == 1) {
                if ($row['v_fine'] == $row['base_fine']) {
                    $level = "1st Offense";
                } elseif ($row['v_fine'] == $row['fine_2nd']) {
                    $level = "2nd Offense";
                } elseif ($row['v_fine'] == $row['fine_3rd']) {
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

    public function findById(int $id): ?TicketEntity {
        // We join the licenses table to get the name, address, etc.
        $sql = "SELECT t.*, l.* FROM tickets t 
                INNER JOIN licenses l ON t.license_id = l.license_id 
                WHERE t.ticket_id = ? 
                LIMIT 1";
                
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new \RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            return null;
        }

        // Now $row contains 'first_name', so hydrate won't crash!
        return $this->hydrate($row);
    }

    public function getImagePath(int $id): ?string {
        $sql = "SELECT proof_image FROM tickets WHERE ticket_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row ? $row['proof_image'] : null;
    }

    public function delete(int $id): bool {
        // First delete items to maintain referential integrity (if no CASCADE)
        $sqlItems = "DELETE FROM ticket_items WHERE ticket_id = ?";
        $stmtItems = $this->conn->prepare($sqlItems);
        if ($stmtItems) {
            $stmtItems->bind_param("i", $id);
            $stmtItems->execute();
        }

        $sqlTicket = "DELETE FROM tickets WHERE ticket_id = ?";
        $stmtTicket = $this->conn->prepare($sqlTicket);
        
        if (!$stmtTicket) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmtTicket->bind_param("i", $id);
        return $stmtTicket->execute();
    }

    public function hydrate(array $row): TicketEntity {
        $ticket = new TicketEntity(
            $this->licenseRepo->hydrate($row),
            (int)$row['ref_number'],
            new DateTime($row['date_of_incident']),
            $row['place_of_incident'],
            $row['notes'],
            TicketStatusEnum::from($row['status']),
            (int)$row['ticket_id'],
            $row['proof_image'] ?? null
        );
        $ticket->setCreatedAt($row['created_at']);
        $ticket->setTotalFine((int)$row['total_fine']);
        return $ticket;
    }

    public function findByIdOnly(int $id): ?array {
        $sql = "SELECT * FROM tickets WHERE ticket_id = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc() ?: null;
    }

    public function updateStatus(int $id, TicketStatusEnum $status): bool {
        $statusValue = $status->value;
        $sql = "UPDATE tickets SET status = ?, updated_at = NOW() WHERE ticket_id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("si", $statusValue, $id);

        if (!$stmt->execute()) {
            throw new RuntimeException("Update Failed: {$stmt->error}");
        }

        return $stmt->affected_rows > 0;
    }

    public function updateRaw(int $id, string $place, ?string $notes, int $total, array $items): void {
        // Update the main tickets table
        $stmt = $this->conn->prepare("UPDATE tickets SET place_of_incident = ?, notes = ?, total_fine = ?, updated_at = NOW() WHERE ticket_id = ?");
        $stmt->bind_param("ssii", $place, $notes, $total, $id);
        $stmt->execute();

        // Delete old items
        $this->conn->query("DELETE FROM ticket_items WHERE ticket_id = $id");

        // Insert new items 
        $itemSql = "INSERT INTO ticket_items(ticket_id, violation_id, name, fine) VALUES (?, ?, ?, ?)";
        $itemStmt = $this->conn->prepare($itemSql);

        foreach ($items as $item) {
            $itemStmt->bind_param("iisi", 
                $id, 
                $item['violation_id'], 
                $item['name'], 
                $item['fine']
            );
            $itemStmt->execute();
        }
    }

    public function findByLtoClientId(string $ltoClientId): array {
        $sql = "SELECT * FROM tickets WHERE lto_client_id = (SELECT id FROM users WHERE lto_client_id = ?) ORDER BY date_of_incident DESC";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            throw new RuntimeException("Prepare Failed: {$this->conn->error}");
        }

        $stmt->bind_param("s", $ltoClientId);

        if (!$stmt->execute()) {
            throw new RuntimeException("Execution Failed: {$stmt->error}");
        }

        $result = $stmt->get_result();
        $tickets = [];
        
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $this->hydrate($row);
        }
        
        $stmt->close();
        return $tickets;
    }

    public function count(): int {
        $sql = "SELECT COUNT(*) as total FROM tickets";
        $result = $this->conn->query($sql);
        
        if (!$result) {
            throw new RuntimeException("Query Failed: {$this->conn->error}");
        }
        
        $row = $result->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }
}
?>