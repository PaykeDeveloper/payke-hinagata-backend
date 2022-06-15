<?php

namespace Database\Seeders\Common;

use App\Models\Common\Permission;
use App\Models\Common\PermissionType;
use App\Models\ModelType;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private const RESOURCES = [
        ['model' => ModelType::user, 'all' => true, 'own' => true,],
        ['model' => ModelType::permission, 'all' => true, 'own' => false,],
        ['model' => ModelType::role, 'all' => true, 'own' => false,],
        ['model' => ModelType::invitation, 'all' => true, 'own' => false,],
        ['model' => ModelType::division, 'all' => true, 'own' => true,],
        ['model' => ModelType::member, 'all' => true, 'own' => true,],
        ['model' => ModelType::project, 'all' => true, 'own' => false,],
    ];

    public function run()
    {
        $ids = [];
        foreach (self::RESOURCES as $resource) {
            /** @var ModelType $model */
            $model = $resource['model'];

            /** @var PermissionType[] $permissions */
            $permissions = [];
            if ($resource['all']) {
                $permissions = array_merge($permissions, PermissionType::ALL_TYPES);
            }
            if ($resource['own']) {
                $permissions = array_merge($permissions, PermissionType::OWN_TYPES);
            }

            foreach ($permissions as $permission) {
                $name = PermissionType::getName($model, $permission);
                /** @var Permission $permission */
                $permission = Permission::query()->updateOrCreate([
                    'name' => $name,
                ]);
                $ids[] = $permission->id;
            }
        }
        Permission::query()->whereNotIn('id', $ids)->delete();
    }
}
