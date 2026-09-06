<?php

namespace App\Policies;

use App\Models\ReportCard;
use App\Models\User;

class ReportCardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isTeacher();
    }

    public function view(User $user, ReportCard $reportCard): bool
    {
        return $user->isTeacher();
    }

    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    public function update(User $user, ReportCard $reportCard): bool
    {
        return $user->isTeacher();
    }

    public function delete(User $user, ReportCard $reportCard): bool
    {
        return $user->isTeacher();
    }
}
