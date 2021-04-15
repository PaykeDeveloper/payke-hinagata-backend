<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    // パーミッションのタイプ
    private array $types = [
        'viewAny',
        'viewAnyAll',
        'view',
        'viewAll',
        'create',
        'createAll',
        'update',
        'updateAll',
        'delete',
        'deleteAll',
    ];

    // 用意するリソース (モデル)
    private array $resources = [
        'user',
        'permission',
        'invitation',
        'division',
        'member',
        'project',
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
                $name = $type . '_' . $resource;
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
