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
        // DB::table('vehicles')->insert([
        //     'vehicle_id' => 1,
        //     'license_id' => 1,
        //     'plate_number' => 'ABC 123',
        //     'mv_file_number' => '1234567890ABC',
        //     'vin' => 'ABC123456789',
        //     'make' => 'Toyota',
        //     'model' => 'Supra',
        //     'year' => 1989,
        //     'color' => 'Pink',
        //     'issue_date' => now(),
        //     'expiry_date' => Carbon::parse('2025-06-12')->addYears(3),
        //     'reg_status' => 'Registered',
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
        $vehicles = [
            [
                'license_id'   => 1,
                'plate_number' => 'ABC 123',
                'mv_file'      => '1234567890ABC',
                'vin'          => 'TYT89SUPRA001',
                'make'         => 'Toyota',
                'model'        => 'Supra',
                'year'         => 1989,
                'color'        => 'Pink',
            ],
            [
                'license_id'   => 2,
                'plate_number' => 'NBG 5521',
                'mv_file'      => '9988776655XYZ',
                'vin'          => 'HND22CIVIC882',
                'make'         => 'Honda',
                'model'        => 'Civic Type R',
                'year'         => 2022,
                'color'        => 'Championship White',
            ],
            [
                'license_id'   => 3,
                'plate_number' => 'ZXC 908',
                'mv_file'      => '1122334455QWE',
                'vin'          => 'MTB21MONTERO3',
                'make'         => 'Mitsubishi',
                'model'        => 'Montero Sport',
                'year'         => 2021,
                'color'        => 'Jet Black',
            ],
            [
                'license_id'   => 4,
                'plate_number' => 'WOW 777',
                'mv_file'      => '7766554433LMN',
                'vin'          => 'FRD23RAPTOR04',
                'make'         => 'Ford',
                'model'        => 'Ranger Raptor',
                'year'         => 2023,
                'color'        => 'Code Orange',
            ],
            [
                'license_id'   => 5,
                'plate_number' => 'GAD 4432',
                'mv_file'      => '4455667788RTY',
                'vin'          => 'NSN20NV350005',
                'make'         => 'Nissan',
                'model'        => 'NV350 Urvan',
                'year'         => 2020,
                'color'        => 'Brilliant Silver',
            ],
            [
                'license_id'   => 6,
                'plate_number' => 'PH 2026',
                'mv_file'      => '5544332211PLO',
                'vin'          => 'TYT24VIOS0006',
                'make'         => 'Toyota',
                'model'        => 'Vios',
                'year'         => 2024,
                'color'        => 'Super Red',
            ],
        ];

        foreach ($vehicles as $v) {
            DB::table('vehicles')->insert([
                'license_id'     => $v['license_id'],
                'plate_number'   => $v['plate_number'],
                'mv_file_number' => $v['mv_file'],
                'vin'            => $v['vin'],
                'make'           => $v['make'],
                'model'          => $v['model'],
                'year'           => $v['year'],
                'color'          => $v['color'],
                'issue_date'     => now(),
                'expiry_date'    => Carbon::parse('2025-06-12')->addYears(3),
                'reg_status'     => 'Registered',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
