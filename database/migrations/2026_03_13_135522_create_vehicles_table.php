<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->integer('vehicle_id', true);
            $table->integer('license_id')->index('fk_license_id');
            $table->string('plate_number', 50)->unique('plate_number');
            $table->string('mv_file_number', 50)->unique('mv_file_number');
            $table->string('vin', 50)->unique('vin');
            $table->string('make', 50);
            $table->string('model', 50);
            $table->integer('year');
            $table->string('color', 50);
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->enum('reg_status', ['Registered', 'Unregistered', 'Expired']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
