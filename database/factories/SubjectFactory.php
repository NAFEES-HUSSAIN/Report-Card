<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Mathematics', 'English', 'Science', 'History', 'Art', 'Geography', 'Physics', 'Chemistry',
        ]);

        return [
            'code' => strtoupper(Str::substr(Str::slug($name, ''), 0, 6)).fake()->unique()->numerify('##'),
            'name' => $name,
        ];
    }
}
