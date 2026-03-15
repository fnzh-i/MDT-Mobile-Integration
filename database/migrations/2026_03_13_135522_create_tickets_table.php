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
        Schema::create('tickets', function (Blueprint $table) {
            $table->integer('ticket_id', true);
            $table->integer('license_id')->nullable()->index('license_id');
            $table->bigInteger('ref_number')->nullable()->unique('ref_number');
            $table->date('date_of_incident');
            $table->string('place_of_incident', 100);
            $table->string('notes', 100)->nullable();
            $table->enum('status', ['Unsettled', 'Settled']);
            $table->integer('total_fine');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
