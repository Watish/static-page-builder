<?php

namespace Watish\StaticPageBuilder\Util;

class ENV
{
    private static array $env = [];

    public static function load(string $filePath): void
    {
        if(file_exists($filePath))
        {
            self::$env = parse_ini_file($filePath,true);
        }
    }

    /**
     * @param string $configKey
     * @return array|null
     */
    public static function getConfig(string $configKey): ?array
    {
        return self::$env[$configKey] ?? null;
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed
     */
    public static function get(string $key,$default=null)
    {
        return self::$env[$key] ?? $default;
    }
}