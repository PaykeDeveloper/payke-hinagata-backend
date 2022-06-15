<?php

namespace App\Models\Common;

use App\Models\ModelType;

enum PermissionType: string
{
    case viewOwn = 'view_own';
    case viewAll = 'view_all';
    case createOwn = 'create_own';
    case createAll = 'create_all';
    case updateOwn = 'update_own';
    case updateAll = 'update_all';
    case deleteOwn = 'delete_own';
    case deleteAll = 'delete_all';

    public const OWN_TYPES = [
        self::viewOwn,
        self::createOwn,
        self::updateOwn,
        self::deleteOwn,
    ];

    public const ALL_TYPES = [
        self::viewAll,
        self::createAll,
        self::updateAll,
        self::deleteAll,
    ];

    public static function getName(ModelType $model, self $permission): string
    {
        return implode("__", [$model->value, $permission->value]);
    }

    public static function getNames(ModelType $model, self ...$permissions): array
    {
        return array_map(function (self $permission) use ($model) {
            return self::getName($model, $permission);
        }, $permissions);
    }

    public static function getOwnNames(ModelType $model): array
    {
        return self::getNames($model, ...self::OWN_TYPES);
    }

    public static function getAllNames(ModelType $model): array
    {
        return self::getNames($model, ...self::ALL_TYPES);
    }
}
