<?php

namespace Watish\StaticPageBuilder\Linker;

class LinkCache
{
    private static array $hash = [];

    public static function hSet($key,$hKey,$value) :void
    {
        self::$hash[$key][$hKey] = $value;
    }

    public static function hGet($key,$hKey)
    {
        return self::$hash[$key][$hKey] ?? null;
    }

    public static function hExists($key,$hKey): bool
    {
        return isset(self::$hash[$key][$hKey]);
    }

    public static function hRem($key,$hKey)
    {
        unset(self::$hash[$key][$hKey]);
    }

    public static function exists($key): bool
    {
        return isset(self::$hash[$key]);
    }
}