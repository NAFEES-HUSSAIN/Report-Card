<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\User;
use App\Support\SystemPermissions;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $this->authorizePermission(SystemPermissions::AdminOverview);

        return view('admin.dashboard', [
            'shellRole' => 'admin',
            'stats' => [
                'teachers' => User::query()->where('role', UserRole::Teacher)->count(),
                'activeTeachers' => User::query()->where('role', UserRole::Teacher)->where('is_active', true)->count(),
                'students' => Student::query()->count(),
                'reportCards' => ReportCard::query()->count(),
                'permissions' => Permission::query()->count(),
            ],
            'recentTeachers' => User::query()
                ->where('role', UserRole::Teacher)
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }

    private function authorizePermission(string $key): void
    {
        abort_unless(auth()->user()?->hasPermission($key), 403);
    }
}
