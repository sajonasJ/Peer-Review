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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->text('review_text'); // Text of the review
            $table->integer('rating'); // Rating for the review (e.g., out of 5)
            $table->foreignId('reviewer_id')->constrained('students')->onDelete('cascade'); // Foreign key to the student who is the reviewer
            $table->foreignId('reviewee_id')->constrained('students')->onDelete('cascade'); // Foreign key to the student who is the reviewee
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade'); // Foreign key to the assessment
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
