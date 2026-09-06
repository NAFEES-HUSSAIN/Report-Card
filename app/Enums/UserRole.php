<?php

namespace App\Enums;

enum UserRole: string
{
    case Teacher = 'teacher';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Teacher => 'Teacher',
            self::Admin => 'Admin',
        };
    }
}
