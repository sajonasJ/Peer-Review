<?php

namespace Database\Seeders;
use App\Models\Assessment;
use Illuminate\Database\Seeder;

class AssessmentsTableSeeder extends Seeder
{
    public function run()
    {
        Assessment::factory()->count(5)->create();
    }
}