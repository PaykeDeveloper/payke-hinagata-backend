<?php

namespace App\Models\Common;

final class PermissionType
{
    public const VIEW_OWN = 'viewOwn';
    public const VIEW_ALL = 'viewAll';
    public const CREATE_OWN = 'createOwn';
    public const CREATE_ALL = 'createAll';
    public const UPDATE_OWN = 'updateOwn';
    public const UPDATE_ALL = 'updateAll';
    public const DELETE_OWN = 'deleteOwn';
    public const DELETE_ALL = 'deleteAll';

    public const OWN_TYPES = [
        self::VIEW_OWN,
        self::CREATE_OWN,
        self::UPDATE_OWN,
        self::DELETE_OWN,
    ];

    public const ALL_TYPES = [
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
        return implode("_", [$resource, $type]);
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
