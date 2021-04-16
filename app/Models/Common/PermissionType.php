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

    public const OWN_TYPES = [
        self::VIEW_ANY,
        self::VIEW_OWN,
        self::CREATE_OWN,
        self::UPDATE_OWN,
        self::DELETE_OWN,
    ];

    public const ALL_TYPES = [
        self::VIEW_ANY_ALL,
        self::VIEW_ALL,
        self::CREATE_ALL,
        self::UPDATE_ALL,
        self::DELETE_ALL,
    ];

    public static function all(): array
    {
        return array_merge(self::OWN_TYPES, self::ALL_TYPES);
    }

    public static function getName(string $type, string $resource): string
    {
        return "{$type}_$resource";
    }

    public static function getOwnNames(string $resource): array
    {
        return array_map(function ($type) use ($resource) {
            return self::getName($type, $resource);
        }, self::OWN_TYPES);
    }

    public static function getAllNames(string $resource): array
    {
        return array_map(function ($type) use ($resource) {
            return self::getName($type, $resource);
        }, self::ALL_TYPES);
    }
}
