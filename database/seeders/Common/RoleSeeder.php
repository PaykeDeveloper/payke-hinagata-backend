<?php

namespace Database\Seeders\Common;

use App\Models\Common\PermissionType;
use App\Models\Common\Role;
use App\Models\Common\UserRole;
use App\Models\Sample\Division;
use App\Models\Sample\Member;
use App\Models\Sample\MemberRole;
use App\Models\Sample\Project;
use App\Models\User;
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
        $data_set = [
            // User Roles
            ['name' => UserRole::ADMIN, 'permissions' => array_merge(
                self::getCrudAllPermissions([User::RESOURCE]),
            )],
            ['name' => UserRole::MANAGER, 'permissions' => array_merge(
                self::getCrudAllPermissions([Division::RESOURCE, Member::RESOURCE]),
                [
                    PermissionType::getName(PermissionType::VIEW, User::RESOURCE),
                    PermissionType::getName(PermissionType::VIEW_ANY, User::RESOURCE),
                ]
            )],

            // Member Roles
            ['name' => MemberRole::MANAGER, 'permissions' => array_merge(
                self::getCrudAllPermissions([Project::RESOURCE]),
                [
                    PermissionType::getName(PermissionType::VIEW, Member::RESOURCE),
                    PermissionType::getName(PermissionType::VIEW_ANY, Member::RESOURCE),

                    PermissionType::getName(PermissionType::VIEW, Division::RESOURCE),
                    PermissionType::getName(PermissionType::VIEW_ANY, Division::RESOURCE),
                ],
            )],
            ['name' => MemberRole::MEMBER, 'permissions' => array_merge(
                [
                    PermissionType::getName(PermissionType::VIEW, Project::RESOURCE),
                    PermissionType::getName(PermissionType::VIEW_ANY, Project::RESOURCE),

                    PermissionType::getName(PermissionType::VIEW, Division::RESOURCE),
                    PermissionType::getName(PermissionType::VIEW_ANY, Division::RESOURCE),
                ],
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
        Role::whereNotIn('id', $ids)->delete();
    }

    private static function getCrudPermissions(array $resources): array
    {
        $permissions = [];

        foreach ($resources as $resource) {
            $permissions = array_merge($permissions, [
                PermissionType::getName(PermissionType::VIEW_ANY, $resource),
                PermissionType::getName(PermissionType::VIEW, $resource),
                PermissionType::getName(PermissionType::CREATE, $resource),
                PermissionType::getName(PermissionType::UPDATE, $resource),
                PermissionType::getName(PermissionType::DELETE, $resource),
            ]);
        }
        return $permissions;
    }

    private static function getCrudAllPermissions(array $resources): array
    {
        $permissions = self::getCrudPermissions($resources);

        foreach ($resources as $resource) {
            $permissions = array_merge($permissions, [
                PermissionType::getName(PermissionType::VIEW_ANY_ALL, $resource),
                PermissionType::getName(PermissionType::VIEW_ALL, $resource),
                PermissionType::getName(PermissionType::CREATE_ALL, $resource),
                PermissionType::getName(PermissionType::UPDATE_ALL, $resource),
                PermissionType::getName(PermissionType::DELETE_ALL, $resource),
            ]);
        }

        return $permissions;
    }
}
