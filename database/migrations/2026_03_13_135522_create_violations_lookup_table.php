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
        Schema::create('violations_lookup', function (Blueprint $table) {
            $table->integer('violation_id', true);
            $table->string('name', 100)->unique('name');
            $table->boolean('is_penalty');
            $table->integer('base_fine');
            $table->integer('2nd_fine')->nullable();
            $table->integer('3rd_fine')->nullable();
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
