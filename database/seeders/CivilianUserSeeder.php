<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CivilianUserSeeder extends Seeder
{
    public function run(): void
    {
        $civilians = [
            [
                'username' => 'john_doe',
                'lto_client_id' => '67-050818-1234568',
                'first_name' => 'John',
                'middle_name' => 'Michael',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password123'),
                'role' => 'CIVILIAN',
            ],
            [
                'username' => 'jane_smith',
                'lto_client_id' => '67-050818-1234569',
                'first_name' => 'Jane',
                'middle_name' => 'Marie',
                'last_name' => 'Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password123'),
                'role' => 'CIVILIAN',
            ],
            [
                'username' => 'bob_wilson',
                'lto_client_id' => '67-050818-1234570',
                'first_name' => 'Bob',
                'middle_name' => 'James',
                'last_name' => 'Wilson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password123'),
                'role' => 'CIVILIAN',
            ],
            [
                'username' => 'maria_garcia',
                'lto_client_id' => '67-050818-1234571',
                'first_name' => 'Maria',
                'middle_name' => 'Isabel',
                'last_name' => 'Garcia',
                'email' => 'maria@example.com',
                'password' => Hash::make('password123'),
                'role' => 'CIVILIAN',
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
