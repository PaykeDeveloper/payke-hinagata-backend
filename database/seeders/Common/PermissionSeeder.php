<?php

namespace Database\Seeders\Common;

use App\Models\Common\Invitation;
use App\Models\Common\Permission;
use App\Models\Common\PermissionType;
use App\Models\Common\Role;
use App\Models\Division\Division;
use App\Models\Division\Member;
use App\Models\Sample\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    // 用意するリソース (モデル)
    private const RESOURCES = [
        ['name' => User::RESOURCE, 'all' => true, 'own' => true,],
        ['name' => Permission::RESOURCE, 'all' => true, 'own' => false,],
        ['name' => Role::RESOURCE, 'all' => true, 'own' => false,],
        ['name' => Invitation::RESOURCE, 'all' => true, 'own' => false,],
        ['name' => Division::RESOURCE, 'all' => true, 'own' => true,],
        ['name' => Member::RESOURCE, 'all' => true, 'own' => true,],
        ['name' => Project::RESOURCE, 'all' => true, 'own' => false,],
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
                $types = array_merge($types, PermissionType::ALL_TYPES);
            }
            if ($resource['own']) {
                $types = array_merge($types, PermissionType::OWN_TYPES);
            }
            $resource_name = $resource['name'];

            foreach ($types as $type) {
                $name = PermissionType::getName($type, $resource_name);
                $permission = Permission::updateOrCreate([
                    'name' => $name,
                ]);
                $ids[] = $permission->id;
            }
        }
        Permission::whereNotIn('id', $ids)->delete();
    }
}
