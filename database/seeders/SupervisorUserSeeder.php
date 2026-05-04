<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SupervisorUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'supervisor'], // Check if 'supervisor' exists
            [
                'lto_client_id' => '67-050818-1234565',
                'first_name' => 'supervisor',
                'middle_name' => null,
                'last_name' => 'supervisor',
                'email' => 'supervisor@example.com',
                'password' => Hash::make('super1234'),
                'role' => 'SUPERVISOR',
            ]
        );
    }
}