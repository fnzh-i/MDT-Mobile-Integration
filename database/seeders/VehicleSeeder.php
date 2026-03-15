<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vehicles')->insert([
            'vehicle_id' => 1,
            'license_id' => 1,
            'plate_number' => 'ABC 123',
            'mv_file_number' => '1234567890ABC',
            'vin' => 'ABC123456789',
            'make' => 'Toyota',
            'model' => 'Supra',
            'year' => 1989,
            'color' => 'Pink',
            'issue_date' => now(),
            'expiry_date' => Carbon::parse('2025-06-12')->addYears(3),
            'reg_status' => 'Registered',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
