<?php

namespace App\Models\Common;

final class PermissionType
{
    public const VIEW_ANY = 'viewAny';
    public const VIEW_ANY_ALL = 'viewAnyAll';
    public const VIEW = 'view';
    public const VIEW_ALL = 'viewAll';
    public const CREATE = 'create';
    public const CREATE_ALL = 'createAll';
    public const UPDATE = 'update';
    public const UPDATE_ALL = 'updateAll';
    public const DELETE = 'delete';
    public const DELETE_ALL = 'deleteAll';

    public static function all(): array
    {
        return [
            self::VIEW_ANY,
            self::VIEW_ANY_ALL,
            self::VIEW,
            self::VIEW_ALL,
            self::CREATE,
            self::CREATE_ALL,
            self::UPDATE,
            self::UPDATE_ALL,
            self::DELETE,
            self::DELETE_ALL,
        ];
    }
}

function getPermissionName(string $type, string $resource): string
{
    return "{$type}_$resource";
}
