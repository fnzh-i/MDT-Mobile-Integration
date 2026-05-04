<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EnforcerUserSeeder extends Seeder
{
    public function run(): void
    {
        $enforcers = [
            [
                'username'      => 'enforcer',
                'lto_client_id' => '67-050818-9876543',
                'first_name'    => 'Juan',
                'middle_name'   => 'Ponce',
                'last_name'     => 'Enrile',
                'email'         => 'enforcer@example.com',
                'password'      => Hash::make('enforce1234'),
                'role'          => 'ENFORCER',
            ],
        ];

        foreach ($enforcers as $enforcer) {
            User::updateOrCreate(
                ['username' => $enforcer['username']],
                $enforcer
            );
        }
    }
}