<?php

namespace Database\Factories;

use App\Enums\Standing;
use App\Models\ReportCard;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReportCard>
 */
class ReportCardFactory extends Factory
{
    public function definition(): array
    {
        $average = fake()->randomFloat(2, 40, 95);

        return [
            'student_id' => Student::factory(),
            'term_id' => Term::factory(),
            'school_class_id' => SchoolClass::factory(),
            'created_by' => User::factory(),
            'average' => $average,
            'total_marks' => round($average * 5, 2),
            'standing' => Standing::fromAverage($average),
            'rank' => null,
            'days_present' => fake()->numberBetween(70, 90),
            'days_absent' => fake()->numberBetween(0, 10),
            'total_days' => 90,
        ];
    }
}
