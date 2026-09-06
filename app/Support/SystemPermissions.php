<?php

namespace App\Support;

final class SystemPermissions
{
    public const TeacherDashboard = 'teacher.dashboard';

    public const TeacherGrades = 'teacher.grades';

    public const TeacherLedger = 'teacher.ledger';

    public const AdminTeachers = 'admin.teachers';

    public const AdminPermissions = 'admin.permissions';

    public const AdminProfiles = 'admin.profiles';

    public const AdminOverview = 'admin.overview';

    /**
     * @return list<array{key: string, name: string, group: string, description: string}>
     */
    public static function definitions(): array
    {
        return [
            [
                'key' => self::TeacherDashboard,
                'name' => 'Teacher dashboard',
                'group' => 'teacher',
                'description' => 'View the teacher overview and class stats.',
            ],
            [
                'key' => self::TeacherGrades,
                'name' => 'Input & edit grades',
                'group' => 'teacher',
                'description' => 'Create and update student report cards.',
            ],
            [
                'key' => self::TeacherLedger,
                'name' => 'Class ledger',
                'group' => 'teacher',
                'description' => 'View rankings, search, and open ledger records.',
            ],
            [
                'key' => self::AdminOverview,
                'name' => 'Admin overview',
                'group' => 'admin',
                'description' => 'Access the principal/admin control dashboard.',
            ],
            [
                'key' => self::AdminTeachers,
                'name' => 'Manage teachers',
                'group' => 'admin',
                'description' => 'Create, update, activate, and deactivate teachers.',
            ],
            [
                'key' => self::AdminPermissions,
                'name' => 'Assign permissions',
                'group' => 'admin',
                'description' => 'Grant or revoke teacher feature permissions.',
            ],
            [
                'key' => self::AdminProfiles,
                'name' => 'Manage profiles',
                'group' => 'admin',
                'description' => 'Update staff profiles, avatars, usernames, and passwords.',
            ],
        ];
    }

    /**
     * @return list<string>
     */
    public static function teacherKeys(): array
    {
        return [
            self::TeacherDashboard,
            self::TeacherGrades,
            self::TeacherLedger,
        ];
    }

    /**
     * @return list<string>
     */
    public static function adminKeys(): array
    {
        return [
            self::AdminOverview,
            self::AdminTeachers,
            self::AdminPermissions,
            self::AdminProfiles,
        ];
    }
}
