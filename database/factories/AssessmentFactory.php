<?php

namespace Database\Factories;
use App\Models\Assessment;
use App\Models\Course;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assessment>
 */
class AssessmentFactory extends Factory
{
    protected $model = Assessment::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'instruction' => $this->faker->paragraph(),
            'num_reviews' => $this->faker->numberBetween(1, 5),
            'max_score' => $this->faker->numberBetween(1, 100),
            'due_date' => $this->faker->date(),
            'due_time' => $this->faker->time(),
            'type' => $this->faker->randomElement(['student-select', 'teacher-assign']),
            'course_id' => Course::inRandomOrder()->first()->id,
        ];
    }
}