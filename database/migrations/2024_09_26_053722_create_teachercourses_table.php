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
        Schema::create('teacher_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->string('course_code');
            $table->foreign('course_code')->references('course_code')->on('courses')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        DB::statement('PRAGMA foreign_keys = OFF;');

        Schema::dropIfExists('teacher_courses');

        // Re-enable foreign key checks
        DB::statement('PRAGMA foreign_keys = ON;');
    }
};
