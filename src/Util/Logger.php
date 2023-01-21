<?php

namespace Watish\StaticPageBuilder\Util;

use League\CLImate\CLImate;

class Logger
{
    private static bool $init = false;
    private static CLImate $cli_mate;

    public static function init() :void
    {
        if(self::$init)
        {
            return;
        }
        self::$cli_mate = new CLImate();
        self::$init = true;
    }

    public static function info(string $msg) :void
    {
        self::$cli_mate->info($msg);
    }

    public static function exception(\Exception $exception) :void
    {
        $listArray = [];
        $listArray["msg"] = $exception->getMessage();
        $listArray["code"] = $exception->getCode();
        $listArray["file"] = $exception->getFile();
        $listArray["line"] = $exception->getLine();
        $listArray["strace"] = $exception->getTraceAsString();

        $climate = self::$cli_mate;
        foreach ($listArray as $name => $text)
        {
            $climate->red("[Exception][{$name}]:{$text}");
        }
    }

    public static function warn(string $msg) :void
    {
        self::$cli_mate->yellow($msg);
    }

    public static function error(string $msg) :void
    {
        self::$cli_mate->red($msg);
    }

    public static function getCliMate(): CLImate
    {
        return self::$cli_mate;
    }
}