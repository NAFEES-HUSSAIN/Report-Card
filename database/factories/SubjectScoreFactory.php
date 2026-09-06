<?php

namespace Database\Factories;

use App\Models\ReportCard;
use App\Models\Subject;
use App\Models\SubjectScore;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubjectScore>
 */
class SubjectScoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'report_card_id' => ReportCard::factory(),
            'subject_id' => Subject::factory(),
            'marks' => fake()->randomFloat(1, 40, 100),
            'remarks' => fake()->optional()->randomElement(['Excellent', 'Very good', 'Good', 'Needs improvement']),
        ];
    }
}
