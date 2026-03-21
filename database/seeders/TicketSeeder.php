<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\TicketService;
use App\DTOs\CreateTicketRequest;
use Illuminate\Support\Facades\DB;
use DateTime;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $license = DB::table('licenses')->first();
        $service = app(TicketService::class);

        // Create 3 tickets for the same license to test the total_fine
        for ($i = 1; $i <= 3; $i++) {
            try {
                $request = new CreateTicketRequest(
                    licenseNumber: $license->license_number,
                    violationIds: [1, 10],
                    dateOfIncident: new DateTime(),
                    placeOfIncident: 'City of Manila',
                    notes: "Test Ticket #{$i} for total_fine calculation",
                    proofImage: "proof_{$i}.jpg"
                );

                $ticketId = $service->createTicket($request);

            } catch (\Exception $e) {
                $this->command->error("Failed at Ticket #{$i}: " . $e->getMessage());
            }
        }
    }
}