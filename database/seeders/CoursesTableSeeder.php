<?php

namespace Database\Seeders;

use App\Models\Course;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        Course::truncate();
        $courses = [
            ['course_code' => 'CS101', 'name' => 'Introduction to Computer Science'],
            ['course_code' => 'MATH201', 'name' => 'Calculus II'],
            ['course_code' => 'PHYS105', 'name' => 'General Physics'],
            ['course_code' => 'HIST150', 'name' => 'World History'],
            ['course_code' => 'ENG202', 'name' => 'English Literature'],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
