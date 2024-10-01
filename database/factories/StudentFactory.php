<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Student;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'snumber' => $this->faker->unique()->regexify('s[0-9]{7}'),
            'password' => bcrypt('123456'),
        ];
    }

    /**
     * Predefined state for a specific student.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function predefinedStudent()
    {
        return $this->state([
            'name' => 'Predefined Student',
            'email' => 'student@example.com',
            'snumber' => 's1234567',
            'password' => bcrypt('1234'),
        ]);
    }
}
