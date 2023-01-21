<?php

namespace Watish\StaticPageBuilder\Scanner;

use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;

class ContentScanner
{
    private static array $files = [];
    private static bool $init = false;

    public static function init()
    {
        if(self::$init)
        {
            return;
        }
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        self::$files = $fileSystem->files("/content/",true);
        self::$init = true;
    }

    public static function getFiles(): array
    {
        return self::$files;
    }

}