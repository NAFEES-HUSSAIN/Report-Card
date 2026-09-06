<?php

namespace App\Support;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GradeCatalog
{
    /**
     * @return list<string>
     */
    public static function termNames(): array
    {
        return [
            '1st Term',
            '2nd Term',
            '3rd Term',
        ];
    }

    /**
     * Subject names in alphabetical order.
     *
     * @return list<string>
     */
    public static function subjectNames(): array
    {
        $names = [
            'Maths',
            'Tamil',
            'English',
            'Sinhala',
            'ICT',
            'History',
            'Art',
            'Commerce',
            'Tamil Lit',
            'English Lit',
            'Islam',
            'Health & PED',
            'Science',
        ];

        sort($names, SORT_NATURAL | SORT_FLAG_CASE);

        return array_values($names);
    }

    public static function ensureCurrentAcademicYear(): AcademicYear
    {
        $year = AcademicYear::current();

        if ($year !== null) {
            return $year;
        }

        AcademicYear::query()->update(['is_current' => false]);

        $start = (int) now()->format('Y');

        return AcademicYear::query()->create([
            'name' => $start.'/'.($start + 1),
            'starts_on' => "{$start}-01-01",
            'ends_on' => ($start + 1).'-12-31',
            'is_current' => true,
        ]);
    }

    public static function resolveTerm(string $termName): Term
    {
        $year = self::ensureCurrentAcademicYear();
        $sortOrder = array_search($termName, self::termNames(), true);
        $sortOrder = $sortOrder === false ? 1 : $sortOrder + 1;

        return Term::query()->firstOrCreate(
            [
                'academic_year_id' => $year->id,
                'name' => $termName,
            ],
            [
                'sort_order' => $sortOrder,
            ],
        );
    }

    public static function resolveClass(string $className): SchoolClass
    {
        $year = self::ensureCurrentAcademicYear();

        return SchoolClass::query()->firstOrCreate(
            [
                'academic_year_id' => $year->id,
                'name' => $className,
            ],
            [
                'section' => null,
            ],
        );
    }

    /**
     * Ensure catalog subjects exist and return them ordered A–Z.
     *
     * @return Collection<int, Subject>
     */
    public static function subjects(): Collection
    {
        foreach (self::subjectNames() as $name) {
            Subject::query()->firstOrCreate(
                ['name' => $name],
                ['code' => self::codeFor($name)],
            );
        }

        return Subject::query()
            ->whereIn('name', self::subjectNames())
            ->orderBy('name')
            ->get();
    }

    private static function codeFor(string $name): string
    {
        $base = strtoupper(Str::of($name)->replace('&', 'AND')->slug('_')->limit(12, ''));

        return $base !== '' ? $base : 'SUBJ';
    }
}
