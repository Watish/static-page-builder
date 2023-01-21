<?php

namespace Watish\StaticPageBuilder\Content;

use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Drawer\ContentDrawer;
use Watish\StaticPageBuilder\Drawer\DirDrawer;

class ContentCollector
{
    /**
     * @return ContentDrawer[]
     */
    public static function files(string $path='/'): array
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $files = $fileSystem->files("/content".$path);
        $drawer_list = [];
        $md_file_list = [];
        foreach ($files as $file_path)
        {
            if(str_ends_with($file_path,".md"))
            {
                $md_file_list = [$file_path] = $fileSystem->lastModified($file_path);
            }
        }
        $files = array_keys($md_file_list);
        foreach ($files as $file_path)
        {
            $drawer_list[] = new ContentDrawer($file_path);
        }
        return $drawer_list;
    }

    /**
     * @param string $path
     * @return ContentDrawer[]
     */
    public static function allFiles(string $path='/') :array
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $files = $fileSystem->allFiles("/content".$path);
        $drawer_list = [];
        $md_file_list = [];
        foreach ($files as $file_path)
        {
            if(str_ends_with($file_path,".md"))
            {
                $md_file_list[$file_path] = $fileSystem->lastModified($file_path);
            }
        }
        arsort($md_file_list);
        $files = array_keys($md_file_list);
        foreach ($files as $file_path)
        {
            $drawer_list[] = new ContentDrawer($file_path);
        }
        return $drawer_list;
    }

    /**
     * @param string $path
     * @return DirDrawer[]
     */
    public static function listDir(string $path="/"): array
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $dirs = $fileSystem->directories("/content".$path);
        $res = [];
        $distantSet = [];
        foreach ($dirs as $dir)
        {
            if(isset($distantSet[$dir]))
            {
                continue;
            }
            if(str_contains($dir,'asset'))
            {
                continue;
            }
            $distantSet[$dir] =new DirDrawer($dir);
        }
        $res = array_values($distantSet);
        return $res;
    }

}