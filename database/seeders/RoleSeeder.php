<?php

namespace Database\Seeders;

use App\Models\Common\MemberRole;
use App\Models\Common\PermissionType;
use App\Models\Common\UserRole;
use App\Models\Role;
use App\Models\Sample\Division;
use App\Models\Sample\Member;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use function App\Models\Common\getPermissionName;


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
                    getPermissionName(PermissionType::VIEW, User::RESOURCE),
                    getPermissionName(PermissionType::VIEW_ANY, User::RESOURCE),
                ]
            )],

            // Member Roles
            ['name' => MemberRole::MANAGER, 'permissions' => array_merge(
                self::getCrudAllPermissions([Project::RESOURCE]),
                [
                    getPermissionName(PermissionType::VIEW, Member::RESOURCE),
                    getPermissionName(PermissionType::VIEW_ANY, Member::RESOURCE),

                    getPermissionName(PermissionType::VIEW, Division::RESOURCE),
                    getPermissionName(PermissionType::VIEW_ANY, Division::RESOURCE),
                ],
            )],
            ['name' => MemberRole::MEMBER, 'permissions' => array_merge(
                [
                    getPermissionName(PermissionType::VIEW, Project::RESOURCE),
                    getPermissionName(PermissionType::VIEW_ANY, Project::RESOURCE),

                    getPermissionName(PermissionType::VIEW, Division::RESOURCE),
                    getPermissionName(PermissionType::VIEW_ANY, Division::RESOURCE),
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
                getPermissionName(PermissionType::VIEW_ANY, $resource),
                getPermissionName(PermissionType::VIEW, $resource),
                getPermissionName(PermissionType::CREATE, $resource),
                getPermissionName(PermissionType::UPDATE, $resource),
                getPermissionName(PermissionType::DELETE, $resource),
            ]);
        }
        return $permissions;
    }

    private static function getCrudAllPermissions(array $resources): array
    {
        $permissions = self::getCrudPermissions($resources);

        foreach ($resources as $resource) {
            $permissions = array_merge($permissions, [
                getPermissionName(PermissionType::VIEW_ANY_ALL, $resource),
                getPermissionName(PermissionType::VIEW_ALL, $resource),
                getPermissionName(PermissionType::CREATE_ALL, $resource),
                getPermissionName(PermissionType::UPDATE_ALL, $resource),
                getPermissionName(PermissionType::DELETE_ALL, $resource),
            ]);
        }

        return $permissions;
    }
}
