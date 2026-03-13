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
        Schema::create('ticket_items', function (Blueprint $table) {
            $table->integer('ticket_id')->index('fk_ticket_items_cascade');
            $table->integer('violation_id')->index('violation_id');
            $table->string('name', 100);
            $table->integer('fine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_items');
    }
};
