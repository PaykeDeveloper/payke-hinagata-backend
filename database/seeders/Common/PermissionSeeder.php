<?php

namespace Database\Seeders\Common;

use App\Models\Common\Invitation;
use App\Models\Common\Permission;
use App\Models\Common\PermissionType;
use App\Models\Common\Role;
use App\Models\Sample\Division;
use App\Models\Sample\Member;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    // パーミッションのタイプ
    private array $types = [
        PermissionType::VIEW_ANY,
        PermissionType::VIEW_ANY_ALL,
        PermissionType::VIEW,
        PermissionType::VIEW_ALL,
        PermissionType::CREATE,
        PermissionType::CREATE_ALL,
        PermissionType::UPDATE,
        PermissionType::UPDATE_ALL,
        PermissionType::DELETE,
        PermissionType::DELETE_ALL,
    ];

    // 用意するリソース (モデル)
    private array $resources = [
        User::RESOURCE,
        Permission::RESOURCE,
        Role::RESOURCE,
        Invitation::RESOURCE,
        Division::RESOURCE,
        Member::RESOURCE,
        Project::RESOURCE,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = [];
        foreach ($this->resources as $resource) {
            foreach ($this->types as $type) {
                $name = PermissionType::getName($type, $resource);
                $permission = Permission::updateOrCreate([
                    'name' => $name,
                ], [
                    'name' => $name,
                ]);
                $ids[] = $permission->id;
            }
        }
        Permission::whereNotIn('id', $ids)->delete();
    }
}
