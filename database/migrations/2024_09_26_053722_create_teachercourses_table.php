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
        Schema::create('teacher_courses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade'); // Foreign key to teachers
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Foreign key to courses
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachercourses');
    }
};
