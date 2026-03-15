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
        Schema::create('persons', function (Blueprint $table) {
            $table->integer('person_id', true);
            $table->string('first_name', 50);
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50);
            $table->string('suffix', 50)->nullable();
            $table->date('date_of_birth');
            $table->string('gender', 50);
            $table->string('address', 100);
            $table->string('nationality', 50);
            $table->string('height', 50);
            $table->string('weight', 50);
            $table->string('eye_color', 50);
            $table->string('blood_type', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
