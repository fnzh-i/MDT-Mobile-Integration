<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $ticket_fine = DB::table('ticket_items')->insertGetId([
            'ticket_id' => 1,
            'violation_id' => 7,
            'name' => 'Driving Without Valid License',
            'fine' => 1000,
        ]);
        DB::table('tickets')->insert([
            'ticket_id' => 1,
            'license_id' => 1,
            'lto_client_id' => '123456789',
            'ref_number' => '1234567890',
            'date_of_incident' => now(),
            'place_of_incident' => 'Manila',
            'notes' => 'test',
            'status' => 'Unsettled',
            'total_fine' => $ticket_fine,
            'created_at' => now(),
            'updated_at' => now(),
            // 'ticket_id' => 1,
            // 'license_id' => 1,
            // 'lto_client_id' => '123456789',
            // 'ref_number' => '1234567890',
            // 'violations' => 1,
            // 'date_of_incident' => now(),
            // 'place_of_incident' => 'Manila',
            // 'notes' => 'test',
            // 'status' => 'Unsettled',
            // 'total_fine' => 1000,
            // 'created_at' => now(),
            // 'updated_at' => now(),
        ]);
    }
}
