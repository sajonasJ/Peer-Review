<?php

namespace Database\Seeders;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        // Create 50 students
        $students = Student::factory()->count(50)->create();
    }
}
