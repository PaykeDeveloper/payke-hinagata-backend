<?php

namespace Database\Seeders\Common;

use App\Models\Common\Permission;
use App\Models\Common\PermissionType;
use App\Models\Common\Role;
use App\Models\Common\UserRole;
use App\Models\Division\MemberRole;
use App\Models\ModelType;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $dataSet = [
            // User Roles
            ['name' => UserRole::ORGANIZER, 'permissions' => array_merge(
                PermissionType::getAllNames(ModelType::permission),
                PermissionType::getAllNames(ModelType::role),
                PermissionType::getAllNames(ModelType::user),
                PermissionType::getAllNames(ModelType::invitation),
            )],
            ['name' => UserRole::MANAGER, 'permissions' => array_merge(
                PermissionType::getAllNames(ModelType::division),
                PermissionType::getAllNames(ModelType::member),
                [
                    PermissionType::getName(ModelType::user, PermissionType::viewAll),
                ]
            )],
            ['name' => UserRole::STAFF, 'permissions' => array_merge(
                [
                    PermissionType::getName(ModelType::role, PermissionType::viewAll),
                    PermissionType::getName(ModelType::division, PermissionType::viewOwn),
                ]
            )],

            // Member Roles
            ['name' => MemberRole::MANAGER, 'permissions' => array_merge(
                PermissionType::getOwnNames(ModelType::division),
                PermissionType::getAllNames(ModelType::member),
                PermissionType::getAllNames(ModelType::project),
            )],
            ['name' => MemberRole::MEMBER, 'permissions' => array_merge(
                [
                    PermissionType::getName(ModelType::division, PermissionType::viewOwn),
                    PermissionType::getName(ModelType::project, PermissionType::viewAll),
                ],
            )],
        ];

        $ids = [];

        /** @var Role $adminRole */
        $adminRole = Role::query()->updateOrCreate(['name' => UserRole::ADMINISTRATOR]);
        $adminRole->syncPermissions(Permission::query()->pluck('name')->all());
        $ids[] = $adminRole->id;

        foreach ($dataSet as $value) {
            $name = $value['name'];
            /** @var Role $role */
            $role = Role::query()->updateOrCreate([
                'name' => $name,
            ]);
            $ids[] = $role->id;

            // 各種パーミッションの割り当
            $permissions = $value['permissions'] ?? null;
            if ($permissions) {
                $role->syncPermissions($permissions);
            }
        }
        Role::query()->whereNotIn('id', $ids)->delete();
    }
}
