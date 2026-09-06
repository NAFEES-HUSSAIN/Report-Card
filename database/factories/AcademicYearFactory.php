<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    public function definition(): array
    {
        $startYear = fake()->unique()->numberBetween(2000, 2090);

        return [
            'name' => $startYear.'/'.($startYear + 1),
            'starts_on' => "{$startYear}-09-01",
            'ends_on' => ($startYear + 1).'-07-31',
            'is_current' => false,
        ];
    }

    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_current' => true,
        ]);
    }
}
