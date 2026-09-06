<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Support\SystemPermissions;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SystemPermissions::definitions() as $permission) {
            Permission::query()->updateOrCreate(
                ['key' => $permission['key']],
                [
                    'name' => $permission['name'],
                    'group' => $permission['group'],
                    'description' => $permission['description'],
                ],
            );
        }
    }
}
