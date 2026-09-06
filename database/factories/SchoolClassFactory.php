<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => 'Form '.fake()->numberBetween(1, 5).fake()->randomElement(['A', 'B', 'C']),
            'section' => null,
        ];
    }
}
