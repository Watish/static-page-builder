<?php

use Watish\StaticPageBuilder\ArgvParser\ArgvParser;
use Watish\StaticPageBuilder\Builder\Builder;
use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Engine\BladeEngine;
use Watish\StaticPageBuilder\Scanner\ContentScanner;
use Watish\StaticPageBuilder\Scanner\PageScanner;
use Watish\StaticPageBuilder\Util\ENV;
use Watish\StaticPageBuilder\Util\Logger;
use Watish\StaticPageBuilder\Watcher\Watcher;

const BASE_DIR = __DIR__;
require_once BASE_DIR.'/vendor/autoload.php';

Logger::init();
BladeEngine::init();
LocalFilesystemConstructor::init();
ENV::load(BASE_DIR.'/.env');
PageScanner::init();;
ContentScanner::init();

$builder = new Builder("output");
$builder->build();

$argvParser = new ArgvParser($argv);
if($argvParser->has("serve"))
{
    $shell = "cd ".BASE_DIR.'/output/ ';
    $shell .= '&& php -S 0.0.0.0:8080';
    Logger::info(exec($shell));
}
if($argvParser->has("watch"))
{
    Logger::info("Start Watching");
    $watcher = new Watcher([
        "content/",
        "template/",
        "source/"
    ]);
    while (1)
    {
        if($watcher->hasChanged())
        {
            Logger::info("rebuild");
            $builder->rebuild();
        }
        sleep(1);
    }
}