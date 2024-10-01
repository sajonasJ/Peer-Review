<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Teacher;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

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
     * Predefined state for a specific teacher.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function predefinedTeacher()
    {
        return $this->state([
            'name' => 'Predefined Teacher',
            'email' => 'teacher@example.com',
            'snumber' => 's0123456',
            'password' => bcrypt('1234'),
        ]);
    }
}
