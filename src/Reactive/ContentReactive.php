<?php

namespace Watish\StaticPageBuilder\Reactive;

use Watish\StaticPageBuilder\Drawer\ContentDrawer;

class ContentReactive
{
    private static string $relativePath = '';

    public static function setPath(string $relativePath)
    {
        self::$relativePath = $relativePath;
    }

    public static function getDrawer(): ContentDrawer
    {
        return new ContentDrawer(self::$relativePath);
    }

}