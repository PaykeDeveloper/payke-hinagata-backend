<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $crud_perms = function ($resources) {
            $perms = [];

            foreach ($resources as $resource) {
                $perms = array_merge($perms, [
                    'viewAny_' . $resource,
                    'view_' . $resource,
                    'create_' . $resource,
                    'update_' . $resource,
                    'delete_' . $resource,
                ]);
            }
            return $perms;
        };

        $admin_crud_perms = function ($resources) use ($crud_perms) {
            $perms = [];

            foreach ($resources as $resource) {
                $perms = array_merge($perms, [
                    'viewAnyAll_' . $resource,
                    'viewAll_' . $resource,
                    'createAll_' . $resource,
                    'updateAll_' . $resource,
                    'deleteAll_' . $resource,
                ]);
            }

            return $perms;
        };

        $data_set = [
            // User Roles
            [ 'name' => 'Super Admin' ],
            [ 'name' => 'Admin', 'permissions' => $admin_crud_perms(['user']) ],
            [ 'name' => 'Manager', 'permissions' => array_merge(
                $admin_crud_perms(['division', 'member']),
                ['view_user', 'viewAny_user']
            )],

            // Member Roles
            [ 'name' => 'Division Manager', 'permissions' => array_merge(
                $admin_crud_perms(['project']),
                ['view_division'],
            )],
            [ 'name' => 'Member', 'permissions' => array_merge(
                $crud_perms(['project']),
                ['view_division'],
            )],
        ];

        foreach ($data_set as $i => $value) {
            $role = Role::updateOrCreate([
                'id' => $i + 1,
            ], [
                'name' => $value['name'],
                'guard_name' => 'web',
            ]);

            // 各種パーミッションの割り当
            $permissions = $value['permissions'] ?? null;
            if ($permissions) {
                $role->syncPermissions($permissions);
            }
        }
    }
}
