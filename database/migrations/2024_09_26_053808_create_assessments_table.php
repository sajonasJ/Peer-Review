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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('title', 20); // Assessment title (up to 20 characters)
            $table->text('instruction'); // Free-text instructions for the assessment
            $table->integer('num_reviews')->unsigned(); // Number of reviews required
            $table->integer('max_score')->unsigned(); // Maximum score (1 to 100)
            $table->date('due_date'); // Date only for the due date
            $table->time('due_time'); // Time only for the due time
            $table->enum('type', ['student-select', 'teacher-assign']); // Type of peer review
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Foreign key to courses
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
