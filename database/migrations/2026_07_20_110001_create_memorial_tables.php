<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A fallen course-mate being remembered.
        Schema::create('fallen_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academy_number')->nullable();
            $table->string('squadron')->nullable();
            $table->string('course')->nullable();
            $table->date('date_of_passing')->nullable();
            $table->text('biography')->nullable();
            $table->string('portrait')->nullable(); // main photo of the fallen member
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Written recollections / tributes from members.
        Schema::create('tributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fallen_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        // Photos members share of themselves with the fallen member.
        Schema::create('memorial_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fallen_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorial_photos');
        Schema::dropIfExists('tributes');
        Schema::dropIfExists('fallen_members');
    }
};
