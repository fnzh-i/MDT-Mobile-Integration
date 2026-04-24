<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tickets = [
            [
                'user_id'   => 1,
                'category'  => 'Technical',
                'message'   => 'Cannot access dashboard. User reporting 403 error when clicking the admin link.',
                'status'    => 'Open',
            ],
            [
                'user_id'   => 2,
                'category'  => 'Billing',
                'message'   => 'License renewal issue. Payment processed but expiry date did not update.',
                'status'    => 'In Progress',
            ],
            [
                'user_id'   => 3,
                'category'  => 'Account',
                'message'   => 'Password reset request. User forgot their security questions and cannot reset password.',
                'status'    => 'Closed',
            ],
            [
                'user_id'   => 4,
                'category'  => 'Technical',
                'message'   => 'Mobile app crashing. The MDT app closes immediately after splash screen on Android 14.',
                'status'    => 'Open',
            ],
            [
                'user_id'   => 5,
                'category'  => 'General',
                'message'   => 'Inquiry about vehicle registration. Asking for requirements regarding heavy vehicle registration.',
                'status'    => 'Open',
            ],
        ];

        foreach ($tickets as $t) {
            // Using updateOrInsert prevents Duplicate Entry errors
            DB::table('support_tickets')->updateOrInsert(
                ['user_id' => $t['user_id'], 'message' => $t['message']], // Unique identifier to check
                [
                    'user_id'   => $t['user_id'],
                    'category'  => $t['category'],
                    'message'   => $t['message'],
                    'status'    => $t['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}