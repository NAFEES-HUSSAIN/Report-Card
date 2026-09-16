<?php

namespace App\Support;

use App\Models\ReportCard;
use App\Models\Student;
use Illuminate\Support\Collection;

class ReportCardPresenter
{
    /**
     * Shape a report card for Blade views (matches PROMPT data contract).
     *
     * @return object{
     *     id: int,
     *     name: string,
     *     index_number: string,
     *     class_name: string,
     *     term: string,
     *     standing: string,
     *     rank: int|string|null,
     *     average: float,
     *     total_marks: float,
     *     days_present: int,
     *     days_absent: int,
     *     total_days: int,
     *     teacher_remark: string|null,
     *     subjects: Collection<int, object>,
     *     report_card_id: int|null,
     *     school_class_id: int|null,
     *     term_id: int|null,
     *     student_id: int
     * }
     */
    public function fromReportCard(ReportCard $reportCard): object
    {
        $reportCard->loadMissing(['student', 'term', 'schoolClass', 'subjectScores.subject']);

        return (object) [
            'id' => $reportCard->student_id,
            'student_id' => $reportCard->student_id,
            'report_card_id' => $reportCard->id,
            'school_class_id' => $reportCard->school_class_id,
            'term_id' => $reportCard->term_id,
            'name' => $reportCard->student->name,
            'index_number' => $reportCard->student->index_number,
            'class_name' => $reportCard->schoolClass->name,
            'term' => $reportCard->term->name,
            'standing' => $reportCard->standing?->value ?? (string) $reportCard->standing,
            'rank' => $reportCard->rank,
            'average' => (float) $reportCard->average,
            'total_marks' => (float) $reportCard->total_marks,
            'days_present' => (int) $reportCard->days_present,
            'days_absent' => (int) $reportCard->days_absent,
            'total_days' => (int) $reportCard->total_days,
            'teacher_remark' => $reportCard->teacher_remark,
            'subjects' => $reportCard->subjectScores->map(fn ($score) => (object) [
                'id' => $score->id,
                'subject_id' => $score->subject_id,
                'name' => $score->subject->name,
                'marks' => (float) $score->marks,
                'remarks' => $score->remarks,
            ])->values(),
            'updated_at' => $reportCard->updated_at,
            'updated_at_human' => $reportCard->updated_at?->diffForHumans(),
        ];
    }

    /**
     * @return object{
     *     id: int,
     *     name: string,
     *     index_number: string,
     *     class_name: string|null,
     *     term: string|null,
     *     standing: string,
     *     rank: null,
     *     average: float,
     *     total_marks: float,
     *     days_present: int,
     *     days_absent: int,
     *     total_days: int,
     *     subjects: Collection<int, never>,
     *     report_card_id: null,
     *     school_class_id: int|null,
     *     term_id: null,
     *     student_id: int
     * }
     */
    public function fromStudentWithoutCard(Student $student): object
    {
        $enrollment = $student->currentEnrollment();
        $enrollment?->loadMissing('schoolClass');

        return (object) [
            'id' => $student->id,
            'student_id' => $student->id,
            'report_card_id' => null,
            'school_class_id' => $enrollment?->school_class_id,
            'term_id' => null,
            'name' => $student->name,
            'index_number' => $student->index_number,
            'class_name' => $enrollment?->schoolClass?->name,
            'term' => null,
            'standing' => '—',
            'rank' => null,
            'average' => 0.0,
            'total_marks' => 0.0,
            'days_present' => 0,
            'days_absent' => 0,
            'total_days' => 0,
            'teacher_remark' => null,
            'subjects' => collect(),
            'updated_at' => null,
            'updated_at_human' => null,
        ];
    }
}
