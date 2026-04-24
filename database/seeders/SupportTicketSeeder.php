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
                'user_id'     => 1,
                'category'    => 'Technical',
                'subject'     => 'Cannot access dashboard',
                'description' => 'User reporting 403 error when clicking the admin link.',
                'priority'    => 'High',
                'status'      => 'Open',
            ],
            [
                'user_id'     => 2,
                'category'    => 'Billing',
                'subject'     => 'License renewal issue',
                'description' => 'Payment processed but expiry date did not update.',
                'priority'    => 'Medium',
                'status'      => 'In Progress',
            ],
            [
                'user_id'     => 3,
                'category'    => 'Account',
                'subject'     => 'Password reset request',
                'description' => 'User forgot their security questions and cannot reset password.',
                'priority'    => 'Low',
                'status'      => 'Closed',
            ],
            [
                'user_id'     => 4,
                'category'    => 'Technical',
                'subject'     => 'Mobile app crashing',
                'description' => 'The MDT app closes immediately after splash screen on Android 14.',
                'priority'    => 'Critical',
                'status'      => 'Open',
            ],
            [
                'user_id'     => 5,
                'category'    => 'General',
                'subject'     => 'Inquiry about vehicle registration',
                'description' => 'Asking for requirements regarding heavy vehicle registration.',
                'priority'    => 'Low',
                'status'      => 'Open',
            ],
        ];

        foreach ($tickets as $t) {
            // Using updateOrInsert prevents Duplicate Entry errors
            DB::table('support_tickets')->updateOrInsert(
                ['subject' => $t['subject']], // Unique identifier to check
                [
                    'user_id'     => $t['user_id'],
                    'category'    => $t['category'],
                    'description' => $t['description'],
                    'priority'    => $t['priority'],
                    'status'      => $t['status'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}