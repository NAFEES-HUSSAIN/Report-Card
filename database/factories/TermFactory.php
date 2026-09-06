<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Term>
 */
class TermFactory extends Factory
{
    public function definition(): array
    {
        return [
            'academic_year_id' => AcademicYear::factory(),
            'name' => fake()->randomElement(['Term 1', 'Term 2', 'Term 3']),
            'starts_on' => now()->startOfMonth(),
            'ends_on' => now()->addMonths(3)->endOfMonth(),
            'sort_order' => fake()->numberBetween(1, 3),
        ];
    }
}
