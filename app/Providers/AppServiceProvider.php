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
        // Hostinger Method B: site root is public_html, app lives in public_html/gradesphere
        $sharedPublic = dirname(base_path());
        if (
            is_file($sharedPublic.DIRECTORY_SEPARATOR.'index.php')
            && is_dir($sharedPublic.DIRECTORY_SEPARATOR.'build')
        ) {
            $this->app->usePublicPath($sharedPublic);
        }
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
