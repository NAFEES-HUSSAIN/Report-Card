<?php

namespace App\Models;

use Database\Factories\SubjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name'])]
class Subject extends Model
{
    /** @use HasFactory<SubjectFactory> */
    use HasFactory;

    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject')->withTimestamps();
    }

    public function subjectScores(): HasMany
    {
        return $this->hasMany(SubjectScore::class);
    }
}
