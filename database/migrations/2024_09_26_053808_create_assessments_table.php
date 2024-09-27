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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('title', 20);
            $table->text('instruction');
            $table->unsignedInteger('num_reviews');
            $table->unsignedInteger('max_score');
            $table->date('due_date');
            $table->time('due_time');
            $table->enum('type', ['student-select', 'teacher-assign']);
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF;');

        Schema::dropIfExists('assessments');
    
        // Re-enable foreign key checks
        DB::statement('PRAGMA foreign_keys = ON;');
    }
};
