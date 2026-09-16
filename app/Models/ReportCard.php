<?php

namespace App\Models;

use App\Enums\Standing;
use Database\Factories\ReportCardFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'student_id',
    'term_id',
    'school_class_id',
    'created_by',
    'average',
    'total_marks',
    'standing',
    'rank',
    'days_present',
    'days_absent',
    'total_days',
    'teacher_remark',
])]
class ReportCard extends Model
{
    /** @use HasFactory<ReportCardFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'average' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'standing' => Standing::class,
            'rank' => 'integer',
            'days_present' => 'integer',
            'days_absent' => 'integer',
            'total_days' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subjectScores(): HasMany
    {
        return $this->hasMany(SubjectScore::class);
    }

    /**
     * Newest report card for a student (prefers the current academic year, any term).
     */
    public static function latestForStudent(Student $student): ?self
    {
        $year = AcademicYear::current();

        return static::query()
            ->with(['student', 'term', 'schoolClass', 'subjectScores.subject'])
            ->where('student_id', $student->id)
            ->when(
                $year,
                fn ($query) => $query->whereHas(
                    'term',
                    fn ($termQuery) => $termQuery->where('academic_year_id', $year->id),
                ),
            )
            ->latest('id')
            ->first();
    }
}
