<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PersonLicenseSeeder extends Seeder
{
    public function run(): void
    {
        // Create the Person 
        $personId = DB::table('persons')->insertGetId([
            'first_name'    => 'Kc Lyn',
            'middle_name'   => 'Constantino',
            'last_name'     => 'Lanuza',
            'suffix'        => null,
            'date_of_birth' => '1994-10-23',
            'gender'        => 'Female',
            'address'       => '123 Rizal St, Makati City, Metro Manila',
            'nationality'   => 'Filipino',
            'height'        => '170cm',
            'weight'        => '55kg', 
            'eye_color'     => 'Brown',   
            'blood_type'    => 'O+',         
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        // Create the License using the ID from above
        DB::table('licenses')->insert([
            'person_id'      => $personId,
            'license_number' => 'D01-12-000567',
            'license_type'   => 'Non-Professional',
            'license_status' => 'Active',
            'dl_codes'       => json_encode(["A", "A1", "B", "B1", "C", "D"]), 
            'issue_date'     => '2025-06-12',
            'expiry_date'    => Carbon::parse('2025-06-12')->addYears(10),
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
    }
}