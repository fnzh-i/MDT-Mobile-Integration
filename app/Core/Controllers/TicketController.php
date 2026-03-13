<?php
namespace App\Core\Controllers;

use App\Services\TicketService;
use App\DTOs\CreateTicketRequest;
use DateTime;
use Throwable;

class TicketController extends BaseController {
    private TicketService $ticketService;

    public function __construct(TicketService $ticketService) {
        $this->ticketService = $ticketService;
    }

    public function create(): void {
        $data = $this->getJsonInput();

        if (empty($data['license_id']) || empty($data['violations'])) {
            $this->sendResponse("Incomplete ticket data provided.", 400);
        }

        try {
            $requestDTO = new CreateTicketRequest(
                (int)$data['license_id'],
                $data['violations'],
                new DateTime($data['date_of_incident']),
                $data['place_of_incident'],
                $data['notes'] ?? ''
            );

            $ticketId = $this->ticketService->createTicket($requestDTO);
            $this->sendResponse(["ticket_id" => $ticketId, "message" => "Ticket created successfully"], 201);
        } catch (Throwable $e) {
            $this->sendResponse($e->getMessage(), 400);
        }
    }
}
?>