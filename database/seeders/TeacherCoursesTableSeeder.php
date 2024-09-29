<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Course;

class TeacherCoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all teachers and courses
        $teachers = Teacher::all();
        $courses = Course::all();

        // Ensure teachers and courses are not empty
        if ($teachers->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No teachers or courses found. Please run TeachersTableSeeder and CoursesTableSeeder first.');
            return;
        }

        // Attach random courses to each teacher
        foreach ($teachers as $teacher) {
            $randomCourses = $courses->random(rand(1, 3))->pluck('course_code'); // Attach between 1 to 3 courses
            foreach ($randomCourses as $course_code) {
                $teacher->courses()->attach($course_code);
            }
        }
    }
}
