<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    // パーミッションのタイプ
    private $types = [
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
    private $resources = [
        'user',
        'permission',
        'division',
        'employee',
        'project',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->resources as $i => $resource) {
            foreach ($this->types as $ti => $type) {
                Permission::updateOrCreate([
                    'id' => ($ti + 1) + ($i * count($this->types)),
                ], [
                    'name' => $type . '_' . $resource,
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
