<?php

namespace Watish\StaticPageBuilder\Reactive;

use Watish\StaticPageBuilder\Drawer\ContentDrawer;
use Watish\StaticPageBuilder\Drawer\DirDrawer;

class ContentDirReactive
{
    private static int $total = 0;
    private static array $drawerList = [];
    private static DirDrawer $dirDrawer;

    /**
     * @return ContentDrawer[]
     */
    public static function getDrawerList(): array
    {
        return self::$drawerList;
    }

    /**
     * @return DirDrawer
     */
    public static function getDirDrawer(): DirDrawer
    {
        return self::$dirDrawer;
    }

    /**
     * @return int
     */
    public static function getTotalNum(): int
    {
        return self::$total;
    }

    public static function setDrawers(array $drawerList)
    {
        self::$total = count($drawerList);
        self::$drawerList = $drawerList;
    }

    public static function setDirDrawer(DirDrawer $drawer)
    {
        self::$dirDrawer = $drawer;
    }
}