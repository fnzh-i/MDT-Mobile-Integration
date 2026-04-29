<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CivilianUserSeeder extends Seeder
{
    public function run(): void
    {
        $civilians = [
            [
                'username'      => 'juan_delacruz',
                'lto_client_id' => '67-050818-1234568',
                'first_name'    => 'Juan',
                'middle_name'   => 'Ponce',
                'last_name'     => 'Dela Cruz',
                'email'         => 'juan.delacruz@example.com',
                'password'      => Hash::make('password123'),
                'role'          => 'CIVILIAN',
            ],
            [
                'username'      => 'maria_ibarra',
                'lto_client_id' => '67-050818-1234569',
                'first_name'    => 'Maria Clara',
                'middle_name'   => 'Santos',
                'last_name'     => 'Ibarra',
                'email'         => 'maria.clara@example.com',
                'password'      => Hash::make('password123'),
                'role'          => 'CIVILIAN',
            ],
            [
                'username'      => 'rafael_luna',
                'lto_client_id' => '67-050818-1234570',
                'first_name'    => 'Rafael',
                'middle_name'   => 'Antonio',
                'last_name'     => 'Luna',
                'email'         => 'rafael.luna@example.com',
                'password'      => Hash::make('password123'),
                'role'          => 'CIVILIAN',
            ],
            [
                'username'      => 'blessy_villanueva',
                'lto_client_id' => '67-050818-1234571',
                'first_name'    => 'Blessy',
                'middle_name'   => 'Grace',
                'last_name'     => 'Villanueva',
                'email'         => 'blessy.grace@example.com',
                'password'      => Hash::make('password123'),
                'role'          => 'CIVILIAN',
            ],
            [
                'username'      => 'emilio_aguinaldo',
                'lto_client_id' => '67-050818-1234572',
                'first_name'    => 'Emilio',
                'middle_name'   => 'Kasilag',
                'last_name'     => 'Aguinaldo',
                'email'         => 'emilio.aguinaldo@example.com',
                'password'      => Hash::make('password123'),
                'role'          => 'CIVILIAN',
            ],
        ];

        foreach ($civilians as $civilian) {

            User::updateOrCreate(
                ['username' => $civilian['username']],
                $civilian
            );
        }
    }
}
