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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations_lookup');
    }
};