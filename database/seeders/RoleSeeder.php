<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;


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
            $perms = $crud_perms($resources);

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
            ['name' => 'Super Admin'],
            ['name' => 'Admin', 'permissions' => $admin_crud_perms(['user'])],
            ['name' => 'Manager', 'permissions' => array_merge(
                $admin_crud_perms(['division', 'member']),
                ['view_user', 'viewAny_user']
            )],

            // Member Roles
            ['name' => 'Division Manager', 'permissions' => array_merge(
                $admin_crud_perms(['project']),
                ['view_member', 'viewAny_member'],
                ['view_division', 'viewAny_division'],
            )],
            ['name' => 'Member', 'permissions' => array_merge(
                ['view_project', 'viewAny_project'],
                ['view_division', 'viewAny_division'],
            )],
        ];

        $ids = [];
        foreach ($data_set as $value) {
            $name = $value['name'];
            $role = Role::updateOrCreate([
                'name' => $name,
            ], [
                'name' => $value['name'],
            ]);
            $ids[] = $role->id;

            // 各種パーミッションの割り当
            $permissions = $value['permissions'] ?? null;
            if ($permissions) {
                $role->syncPermissions($permissions);
            }
        }
    }
}
