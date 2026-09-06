<?php

namespace App\Enums;

enum Standing: string
{
    case Distinction = 'Distinction';
    case Credit = 'Credit';
    case Pass = 'Pass';
    case Fail = 'Fail';

    public static function fromAverage(float $average): self
    {
        return match (true) {
            $average >= 80 => self::Distinction,
            $average >= 65 => self::Credit,
            $average >= 50 => self::Pass,
            default => self::Fail,
        };
    }

    public function isPassing(): bool
    {
        return $this !== self::Fail;
    }
}
