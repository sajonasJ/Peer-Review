<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Run all the seeders
        $this->call([
            CoursesTableSeeder::class,
            StudentsTableSeeder::class,
            TeachersTableSeeder::class,
            AssessmentsTableSeeder::class,
            ReviewsTableSeeder::class,
            StudentCoursesTableSeeder::class,
            TeacherCoursesTableSeeder::class,
        ]);
    }
}