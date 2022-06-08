<?php

namespace App\Models\Common;

final class PermissionType
{
    public const VIEW_OWN = 'view_own';
    public const VIEW_ALL = 'view_all';
    public const CREATE_OWN = 'create_own';
    public const CREATE_ALL = 'create_all';
    public const UPDATE_OWN = 'update_own';
    public const UPDATE_ALL = 'update_all';
    public const DELETE_OWN = 'delete_own';
    public const DELETE_ALL = 'delete_all';

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
        return implode("__", [$resource, $type]);
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
