<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        $password = Hash::make('password');

        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@gradesphere.test'],
            [
                'name' => 'Principal Admin',
                'username' => 'principal',
                'password' => $password,
                'role' => UserRole::Admin,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $teachers = [
            ['name' => 'Alex Morgan', 'email' => 'teacher@gradesphere.test', 'username' => 'alex.morgan', 'active' => true],
            ['name' => 'Jordan Blake', 'email' => 'teacher2@gradesphere.test', 'username' => 'jordan.blake', 'active' => true],
            ['name' => 'Maya Chen', 'email' => 'maya.chen@gradesphere.test', 'username' => 'maya.chen', 'active' => false],
        ];

        foreach ($teachers as $teacherData) {
            $teacher = User::query()->updateOrCreate(
                ['email' => $teacherData['email']],
                [
                    'name' => $teacherData['name'],
                    'username' => $teacherData['username'],
                    'password' => $password,
                    'role' => UserRole::Teacher,
                    'is_active' => $teacherData['active'],
                    'email_verified_at' => now(),
                ],
            );

            if ($teacherData['active']) {
                $teacher->grantDefaultTeacherPermissions();
            } else {
                $teacher->permissions()->detach();
            }
        }

        unset($admin);
    }
}
