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
    private const OWN_TYPES = [
        PermissionType::VIEW_ANY,
        PermissionType::VIEW_OWN,
        PermissionType::CREATE_OWN,
        PermissionType::UPDATE_OWN,
        PermissionType::DELETE_OWN,
    ];

    private const ALL_TYPES = [
        PermissionType::VIEW_ANY_ALL,
        PermissionType::VIEW_ALL,
        PermissionType::CREATE_ALL,
        PermissionType::UPDATE_ALL,
        PermissionType::DELETE_ALL,
    ];

    // 用意するリソース (モデル)
    private const RESOURCES = [
        ['name' => User::RESOURCE, 'all' => true, 'own' => true,],
        ['name' => Permission::RESOURCE, 'all' => true, 'own' => false,],
        ['name' => Role::RESOURCE, 'all' => true, 'own' => false,],
        ['name' => Invitation::RESOURCE, 'all' => true, 'own' => false,],
        ['name' => Division::RESOURCE, 'all' => true, 'own' => true,],
        ['name' => Member::RESOURCE, 'all' => true, 'own' => true,],
        ['name' => Project::RESOURCE, 'all' => true, 'own' => true,],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = [];
        foreach (self::RESOURCES as $resource) {
            $types = [];
            if ($resource['all']) {
                $types = array_merge($types, self::ALL_TYPES);
            }
            if ($resource['own']) {
                $types = array_merge($types, self::OWN_TYPES);
            }
            $resource_name = $resource['name'];

            foreach ($types as $type) {
                $name = PermissionType::getName($type, $resource_name);
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
