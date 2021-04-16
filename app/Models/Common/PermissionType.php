<?php

namespace App\Models\Common;

final class PermissionType
{
    public const VIEW_ANY = 'viewAny';
    public const VIEW_ANY_ALL = 'viewAnyAll';
    public const VIEW_OWN = 'view';
    public const VIEW_ALL = 'viewAll';
    public const CREATE_OWN = 'create';
    public const CREATE_ALL = 'createAll';
    public const UPDATE_OWN = 'update';
    public const UPDATE_ALL = 'updateAll';
    public const DELETE_OWN = 'delete';
    public const DELETE_ALL = 'deleteAll';

    public static function all(): array
    {
        return [
            self::VIEW_ANY,
            self::VIEW_ANY_ALL,
            self::VIEW_OWN,
            self::VIEW_ALL,
            self::CREATE_OWN,
            self::CREATE_ALL,
            self::UPDATE_OWN,
            self::UPDATE_ALL,
            self::DELETE_OWN,
            self::DELETE_ALL,
        ];
    }

    public static function getName(string $type, string $resource): string
    {
        return "{$type}_$resource";
    }
}
