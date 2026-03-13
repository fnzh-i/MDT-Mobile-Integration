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
        Schema::create('licenses', function (Blueprint $table) {
            $table->integer('license_id', true);
            $table->integer('person_id')->index('person_id');
            $table->string('license_number', 50)->unique('license_number');
            $table->enum('license_type', ['Professional', 'Non-Professional', 'Student Permit']);
            $table->enum('license_status', ['Active', 'Revoked', 'Expired']);
            $table->string('dl_codes', 50);
            $table->date('issue_date');
            $table->date('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
