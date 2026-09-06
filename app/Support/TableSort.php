<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class TableSort
{
    /**
     * @param  array<string, string>  $columns  request key => sql column / order expression
     * @return array{0: string, 1: string}
     */
    public static function from(Request $request, array $columns, string $default, string $defaultDirection = 'asc'): array
    {
        $sort = (string) $request->string('sort');
        $direction = strtolower((string) $request->string('direction', $defaultDirection));

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = $defaultDirection;
        }

        if (! array_key_exists($sort, $columns)) {
            $sort = $default;
        }

        return [$sort, $direction];
    }

    /**
     * @param  Builder<*>  $query
     * @param  array<string, string>  $columns
     * @return Builder<*>
     */
    public static function apply(Builder $query, Request $request, array $columns, string $default, string $defaultDirection = 'asc'): Builder
    {
        [$sort, $direction] = self::from($request, $columns, $default, $defaultDirection);

        return $query->orderBy($columns[$sort], $direction);
    }

    /**
     * Build a query string that toggles sort for a column while keeping other params.
     *
     * @param  array<string, mixed>  $extra
     */
    public static function url(Request $request, string $column, string $currentSort, string $currentDirection, array $extra = []): string
    {
        $direction = ($currentSort === $column && $currentDirection === 'asc') ? 'desc' : 'asc';

        return $request->fullUrlWithQuery(array_merge($extra, [
            'sort' => $column,
            'direction' => $direction,
        ]));
    }
}
