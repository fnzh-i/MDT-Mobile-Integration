<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PersonLicenseSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to prevent duplicate key errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('licenses')->truncate();
        DB::table('persons')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $data = [
            [
                'first_name'    => 'Kc Lyn',
                'middle_name'   => 'Constantino',
                'last_name'     => 'Lanuza',
                'date_of_birth' => '1994-10-23',
                'gender'        => 'Female',
                'address'       => '123 Rizal St, Makati City',
                'license_no'    => 'D01-12-000567',
                'type'          => 'Non-Professional',
            ],
            [
                'first_name'    => 'Juan',
                'middle_name'   => 'Ponce',
                'last_name'     => 'Dela Cruz',
                'date_of_birth' => '1988-05-15',
                'gender'        => 'Male',
                'address'       => '456 Quezon Ave, Quezon City',
                'license_no'    => 'N02-05-889234',
                'type'          => 'Professional',
            ],
            [
                'first_name'    => 'Maria Clara',
                'middle_name'   => 'Santos',
                'last_name'     => 'Ibarra',
                'date_of_birth' => '1992-12-12',
                'gender'        => 'Female',
                'address'       => '789 Session Rd, Baguio City',
                'license_no'    => 'D15-22-114455',
                'type'          => 'Non-Professional',
            ],
            [
                'first_name'    => 'Rafael',
                'middle_name'   => 'Antonio',
                'last_name'     => 'Luna',
                'date_of_birth' => '1990-08-30',
                'gender'        => 'Male',
                'address'       => '101 Vigan St, Ilocos Sur',
                'license_no'    => 'N09-15-667788',
                'type'          => 'Professional',
            ],
            [
                'first_name'    => 'Blessy',
                'middle_name'   => 'Grace',
                'last_name'     => 'Villanueva',
                'date_of_birth' => '1997-01-05',
                'gender'        => 'Female',
                'address'       => '202 JP Laurel Ave, Davao City',
                'license_no'    => 'D11-20-990011',
                'type'          => 'Non-Professional',
            ],
            [
                'first_name'    => 'Emilio',
                'middle_name'   => 'Kasilag',
                'last_name'     => 'Aguinaldo',
                'date_of_birth' => '1985-03-22',
                'gender'        => 'Male',
                'address'       => '303 Kawit St, Cavite',
                'license_no'    => 'N04-10-334422',
                'type'          => 'Professional',
            ],
        ];

        foreach ($data as $entry) {
            $personId = DB::table('persons')->insertGetId([
                'first_name'    => $entry['first_name'],
                'middle_name'   => $entry['middle_name'],
                'last_name'     => $entry['last_name'],
                'suffix'        => null,
                'date_of_birth' => $entry['date_of_birth'],
                'gender'        => $entry['gender'],
                'address'       => $entry['address'],
                'nationality'   => 'Filipino',
                'height'        => '170cm',
                'weight'        => '60kg',
                'eye_color'     => 'Brown',
                'blood_type'    => 'O+',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            DB::table('licenses')->insert([
                'person_id'      => $personId,
                'license_number' => $entry['license_no'],
                'license_type'   => $entry['type'],
                'license_status' => 'Active',
                'dl_codes'       => implode(', ', ['A','A1','B','B1','C','D']),
                'issue_date'     => '2025-06-12',
                'expiry_date'    => Carbon::parse('2025-06-12')->addYears(10),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}