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
        $password = Hash::make('password');

        collect([
            ['name' => 'GradeSphere Admin', 'email' => 'admin@gradesphere.test', 'role' => UserRole::Admin],
            ['name' => 'Alex Morgan', 'email' => 'teacher@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Jordan Blake', 'email' => 'teacher2@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Maya Chen', 'email' => 'maya.chen@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Omar Hassan', 'email' => 'omar.hassan@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Elena Rossi', 'email' => 'elena.rossi@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Daniel Okoye', 'email' => 'daniel.okoye@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Sofia Alvarez', 'email' => 'sofia.alvarez@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Noah Patel', 'email' => 'noah.patel@gradesphere.test', 'role' => UserRole::Teacher],
            ['name' => 'Ava Thompson', 'email' => 'ava.thompson@gradesphere.test', 'role' => UserRole::Teacher],
        ])->each(fn (array $user) => User::query()->updateOrCreate(
            ['email' => $user['email']],
            [
                'name' => $user['name'],
                'role' => $user['role'],
                'password' => $password,
                'email_verified_at' => now(),
            ],
        ));
    }
}
