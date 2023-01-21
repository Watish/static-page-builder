<?php

namespace Watish\StaticPageBuilder\Linker;

class JobQueue
{
    private static array $data = [];

    public static function push($data)
    {
        self::$data[] = $data;
    }

    public static function pop()
    {
        return array_shift(self::$data);
    }

    public static function isEmpty(): bool
    {
        return empty(self::$data);
    }
}