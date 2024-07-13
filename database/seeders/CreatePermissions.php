<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreatePermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'employee-list',
            'employee-create',
            'employee-edit',
            'employee-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permissions-list',
            'permissions-create',
            'permissions-edit',
            'permissions-delete',
        ];

        foreach ($data as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
