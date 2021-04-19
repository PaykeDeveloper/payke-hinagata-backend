<?php

namespace Database\Seeders\Common;

use App\Models\Common\Invitation;
use App\Models\Common\PermissionType;
use App\Models\Common\Role;
use App\Models\Common\UserRole;
use App\Models\Division\Division;
use App\Models\Division\Member;
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
                PermissionType::getAllNames(User::RESOURCE),
                PermissionType::getAllNames(Invitation::RESOURCE),
            )],
            ['name' => UserRole::MANAGER, 'permissions' => array_merge(
                PermissionType::getAllNames(Division::RESOURCE),
                PermissionType::getAllNames(Member::RESOURCE),
                [
                    PermissionType::getName(PermissionType::VIEW_ALL, User::RESOURCE),
                ]
            )],
            ['name' => UserRole::STAFF, 'permissions' => array_merge(
                PermissionType::getOwnNames(Division::RESOURCE),
            )],

            // Member Roles
            ['name' => MemberRole::MANAGER, 'permissions' => array_merge(
                PermissionType::getAllNames(Project::RESOURCE),
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
}
