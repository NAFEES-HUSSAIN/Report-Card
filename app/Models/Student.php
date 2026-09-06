<?php

namespace App\Models;

use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['index_number', 'name'])]
class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory, SoftDeletes;

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function reportCards(): HasMany
    {
        return $this->hasMany(ReportCard::class);
    }

    public function currentEnrollment(): ?Enrollment
    {
        $yearId = AcademicYear::current()?->id;

        if ($yearId === null) {
            return $this->enrollments()->latest('id')->first();
        }

        return $this->enrollments()->where('academic_year_id', $yearId)->first();
    }
}
