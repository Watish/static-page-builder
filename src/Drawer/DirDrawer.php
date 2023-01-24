<?php

namespace Watish\StaticPageBuilder\Drawer;

use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Linker\ContentDirLinker;
use Watish\StaticPageBuilder\Util\Logger;

class DirDrawer
{
    private string $path;

    public function __construct(string $relativePath)
    {
        $this->path = $relativePath;
    }

    public function getDirName() :string
    {
        if(!str_contains($this->path,'/'))
        {
            return $this->path;
        }
        $path = $this->path;
        $path = explode('/',$path);
        return $path[count($path)-1];
    }

    /**
     * @return ContentDrawer[]
     */
    public function getAllContentDrawer(): array
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $allFiles = $fileSystem->allFiles($this->path);
        $res = [];
        foreach ($allFiles as $file_path)
        {
            if(str_ends_with($file_path,'.md'))
            {
                $res[] = new ContentDrawer($file_path);
            }
        }
        return $res;
    }

    /**
     * @param string $format
     * @return ContentDrawer[][]
     */
    public function sortByDate(string $format="Y年m月d日"): array
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $allFiles = $fileSystem->allFiles($this->path);
        $resHash= [];
        $resList = [];
        foreach ($allFiles as $file_path)
        {
            if(str_ends_with($file_path,'.md'))
            {
                $lastModified = $fileSystem->lastModified($file_path);
                $resList[$file_path] = $lastModified;
            }
        }
        arsort($resList);
        foreach ($resList as $file_path => $modified_time)
        {
            $modified_date = date($format,$modified_time);
            $resHash[$modified_date][] = new ContentDrawer($file_path);
        }
        return $resHash;
    }

    public function linker(string $view): ContentDirLinker
    {
        return new ContentDirLinker($this->path,$view);
    }

}