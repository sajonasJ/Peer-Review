<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Database\Seeder;

class StudentCoursesTableSeeder extends Seeder
{
    public function run()
    {
        // Fetch all students and courses
        $students = Student::all();
        $courses = Course::all();

        // Ensure students and courses are not empty
        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No students or courses found. Please run StudentsTableSeeder and CoursesTableSeeder first.');
            return;
        }

        // Attach random courses to each student
        foreach ($students as $student) {
            $randomCourses = $courses->random(rand(1, 3))->pluck('course_code'); // Attach between 1 to 3 courses
            $student->courses()->attach($randomCourses);
        }
    }
}
