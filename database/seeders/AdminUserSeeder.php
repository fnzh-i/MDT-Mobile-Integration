<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'], // Check if 'admin' exists
            [
                'lto_client_id' => '67-050818-1234566',
                'first_name' => 'Admin',
                'middle_name' => null,
                'last_name' => 'Nimda',
                'email' => 'admin@example.com',
                'password' => Hash::make('nimda12345'),
                'role' => 'ADMIN',
            ]
        );
    }
}