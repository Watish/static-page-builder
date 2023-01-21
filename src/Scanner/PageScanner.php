<?php

namespace Watish\StaticPageBuilder\Scanner;

use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;

class PageScanner
{
    private static array $pageConfig = [];

    public static function init() :void
    {
        $pageConfig = require_once BASE_DIR.'/config/page.php';
        self::$pageConfig = $pageConfig;
    }

    public static function getAllViewFiles(): array
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $pathList = $fileSystem->files("/template".self::$pageConfig["page_dir"],true);
        $resList = [];
        $resList[] = self::$pageConfig["entry_view"];
        foreach ($pathList as $path)
        {
            if(str_ends_with($path,'.blade.php'))
            {
                $path = str_replace('/','.',$path);
                $path = preg_replace('/^(template\.)*/','',$path);
                $path = preg_replace('/(\.blade\.php)$/','',$path);
                $resList[] = $path;
            }
        }
        return $resList;
    }

    public static function getPageDir() :string
    {
        return '/template'.self::$pageConfig["page_dir"];
    }
}