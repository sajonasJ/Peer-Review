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
        // Drop the existing `reviews` table if it exists
        Schema::dropIfExists('reviews');

        // Recreate the `reviews` table
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('review_text');
            $table->unsignedTinyInteger('rating');
            $table->foreignId('reviewer_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('reviewee_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->timestamps();
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
