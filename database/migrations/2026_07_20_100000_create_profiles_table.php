<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A member's course/service and personal details.
     * One-to-one with users. Kept separate from the users table so the
     * core login identity stays lean and the profile can grow over time.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // --- Course / service identity ---
            $table->string('academy_number')->nullable();
            $table->string('squadron')->nullable();
            $table->string('course')->nullable(); // e.g. "102nd"

            // --- Contact ---
            $table->string('phone')->nullable();
            $table->text('address')->nullable();

            // --- Personal ---
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_marriage')->nullable();
            $table->text('bio')->nullable(); // free text: current city, service history, etc.

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
