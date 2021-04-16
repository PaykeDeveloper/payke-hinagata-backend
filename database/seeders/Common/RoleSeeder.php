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
                self::getAllPermissions(User::RESOURCE),
            )],
            ['name' => UserRole::MANAGER, 'permissions' => array_merge(
                self::getAllPermissions(Division::RESOURCE),
                self::getAllPermissions(Member::RESOURCE),
                [
                    PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE),
                ]
            )],
            ['name' => UserRole::STAFF, 'permissions' => array_merge(
                self::getOwnPermissions(Division::RESOURCE),
            )],

            // Member Roles
            ['name' => MemberRole::MANAGER, 'permissions' => array_merge(
                self::getAllPermissions(Project::RESOURCE),
                [
                    PermissionType::getName(PermissionType::VIEW_ALL, Member::RESOURCE),
                ],
            )],
            ['name' => MemberRole::MEMBER, 'permissions' => array_merge(
                [
                    PermissionType::getName(PermissionType::VIEW_OWN, Project::RESOURCE),
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

    private static function getOwnPermissions(string $resource): array
    {
        return [
            PermissionType::getName(PermissionType::VIEW_ANY, $resource),
            PermissionType::getName(PermissionType::VIEW_OWN, $resource),
            PermissionType::getName(PermissionType::CREATE_OWN, $resource),
            PermissionType::getName(PermissionType::UPDATE_OWN, $resource),
            PermissionType::getName(PermissionType::DELETE_OWN, $resource),
        ];
    }

    private static function getAllPermissions(string $resource): array
    {
        return [
            PermissionType::getName(PermissionType::VIEW_ANY_ALL, $resource),
            PermissionType::getName(PermissionType::VIEW_ALL, $resource),
            PermissionType::getName(PermissionType::CREATE_ALL, $resource),
            PermissionType::getName(PermissionType::UPDATE_ALL, $resource),
            PermissionType::getName(PermissionType::DELETE_ALL, $resource),
        ];
    }
}
