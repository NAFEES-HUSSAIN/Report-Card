<?php

namespace App\Providers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();

        Route::bind('teacher', function (string $value): User {
            return User::query()
                ->where('role', UserRole::Teacher)
                ->whereKey($value)
                ->firstOrFail();
        });
    }
}
