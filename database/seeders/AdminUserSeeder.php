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
                'lto_client_id' => '67-050818-1234567',
                'first_name' => 'admin',
                'middle_name' => null,
                'last_name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('nimda12345'),
                'role' => 'ADMIN',
            ]
        );
    }
}