<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'index_number' => 'STU-'.now()->year.'-'.fake()->unique()->numerify('####'),
            'name' => fake()->name(),
        ];
    }
}
