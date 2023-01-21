<?php

namespace Watish\StaticPageBuilder\Constructor;

use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local;

class LocalFilesystemConstructor
{

    private static FilesystemAdapter $illuminate_filesystem;

    public static function init(): void
    {
        $illuminate_filesystem = new FilesystemAdapter(new \League\Flysystem\Filesystem(
            new Local(BASE_DIR.'/')
        ));
        if($illuminate_filesystem->exists('/cache/'))
        {
            $illuminate_filesystem->deleteDirectory('/cache/');
        }
        self::$illuminate_filesystem = $illuminate_filesystem;
    }

    /**
     * @return FilesystemAdapter
     */
    public static function getIlluminateFilesystem(): FilesystemAdapter
    {
        return self::$illuminate_filesystem;
    }
}