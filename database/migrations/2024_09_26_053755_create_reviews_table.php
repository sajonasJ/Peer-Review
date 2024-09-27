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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->text('review_text');
            $table->integer('rating');
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
    
        DB::statement('PRAGMA foreign_keys = OFF;');

        Schema::dropIfExists('reviews');
    
        // Re-enable foreign key checks
        DB::statement('PRAGMA foreign_keys = ON;');
    }
};
