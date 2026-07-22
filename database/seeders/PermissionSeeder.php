<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard', 'web');

        $roles = ['admin', 'user'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => $guard,
            ]);
        }

        $modules = [

            'client' => [
                'view',
                'create',
                'update',
                'delete',
            ],

            'item' => [
                'view',
                'create',
                'update',
                'delete',
            ],

            'user' => [
                'view',
                'create',
                'update',
                'delete',
            ],

            'tenant' => [
                'view',
                'create',
                'update',
                'delete',
            ],
        ];

        foreach ($modules as $module => $actions) {

            foreach ($actions as $action) {

                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
