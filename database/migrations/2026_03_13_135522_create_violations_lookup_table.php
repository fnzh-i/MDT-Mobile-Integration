<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('violations_lookup', function (Blueprint $table) {
            $table->id('violation_id');
            $table->string('name', 100)->unique();
            $table->boolean('is_penalty');
            $table->integer('base_fine');
            $table->integer('fine_2nd')->nullable(); // Changed from 2nd_fine
            $table->integer('fine_3rd')->nullable(); // Changed from 3rd_fine
        });

        // Insert the specific data provided
        DB::table('violations_lookup')->insert([
            ['violation_id' => 1, 'name' => 'Driving Without Valid License', 'is_penalty' => 0, 'base_fine' => 3000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 2, 'name' => 'Failure To Carry License', 'is_penalty' => 0, 'base_fine' => 1000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 3, 'name' => 'Fake Driver\'s License', 'is_penalty' => 0, 'base_fine' => 3000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 4, 'name' => 'Driving Unregistered Vehicle', 'is_penalty' => 0, 'base_fine' => 10000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 5, 'name' => 'Illegal Modifications', 'is_penalty' => 0, 'base_fine' => 5000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 6, 'name' => 'Defective/Improper Equipment', 'is_penalty' => 0, 'base_fine' => 5000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 7, 'name' => 'Reckless Driving', 'is_penalty' => 1, 'base_fine' => 2000, 'fine_2nd' => 3000, 'fine_3rd' => 10000],
            ['violation_id' => 8, 'name' => 'No Seatbelt', 'is_penalty' => 1, 'base_fine' => 1000, 'fine_2nd' => 3000, 'fine_3rd' => 5000],
            ['violation_id' => 9, 'name' => 'No Helmet', 'is_penalty' => 1, 'base_fine' => 1500, 'fine_2nd' => 3000, 'fine_3rd' => 5000],
            ['violation_id' => 10, 'name' => 'Driving Under Influence', 'is_penalty' => 1, 'base_fine' => 20000, 'fine_2nd' => 60000, 'fine_3rd' => 100000],
            ['violation_id' => 11, 'name' => 'Obstruction', 'is_penalty' => 0, 'base_fine' => 1000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 12, 'name' => 'No OR/CR', 'is_penalty' => 0, 'base_fine' => 1000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 13, 'name' => 'Overloading Passengers', 'is_penalty' => 0, 'base_fine' => 2000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 14, 'name' => 'Overspeeding', 'is_penalty' => 0, 'base_fine' => 1000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 15, 'name' => 'Beating The Red Light', 'is_penalty' => 0, 'base_fine' => 1000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 16, 'name' => 'Illegal Parking', 'is_penalty' => 0, 'base_fine' => 1000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 17, 'name' => 'Using Phone While Driving', 'is_penalty' => 0, 'base_fine' => 1000, 'fine_2nd' => null, 'fine_3rd' => null],
            ['violation_id' => 18, 'name' => 'Counterflow', 'is_penalty' => 0, 'base_fine' => 2000, 'fine_2nd' => null, 'fine_3rd' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations_lookup');
    }
};