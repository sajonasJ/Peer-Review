<?php

namespace Database\Factories;
use App\Models\Assessment; 
use App\Models\Student;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reviews>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\Review::class;

    public function definition()
    {
        return [
            'review_text' => $this->faker->paragraph(),
            'rating' => $this->faker->numberBetween(1, 5),
            'reviewer_id' => Student::inRandomOrder()->first()->id,
            'reviewee_id' => Student::inRandomOrder()->first()->id,
            'assessment_id' => Assessment::inRandomOrder()->first()->id,
        ];
    }
}


