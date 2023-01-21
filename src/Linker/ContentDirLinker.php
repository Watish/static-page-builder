<?php

namespace Watish\StaticPageBuilder\Linker;

use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Drawer\ContentDrawer;
use Watish\StaticPageBuilder\Drawer\DirDrawer;
use Watish\StaticPageBuilder\Engine\BladeEngine;
use Watish\StaticPageBuilder\Output\Output;
use Watish\StaticPageBuilder\Reactive\ContentDirReactive;

class ContentDirLinker
{
    private string $dirPath;
    private string $view;

    public function __construct(string $dirPath, string $view)
    {
        $this->dirPath = $dirPath;
        $this->view = $view;
    }

    public function getLink()
    {
        if(LinkCache::hExists($this->dirPath,$this->view))
        {
            return LinkCache::hGet($this->dirPath,$this->view);
        }
        $outPath = $this->dirPath.'/index.html';
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $allFiles = $fileSystem->allFiles($this->dirPath);
        $drawerList = [];
        $md_file_list = [];
        foreach ($allFiles as $filePath)
        {
            if(str_ends_with($filePath,".md"))
            {
                $md_file_list[$filePath] = $fileSystem->lastModified($filePath);
            }
        }
        arsort($md_file_list);
        $allFiles = array_keys($md_file_list);
        foreach ($allFiles as $filePath)
        {
            $drawerList[] = new ContentDrawer($filePath);
        }
        JobQueue::push(function() use ($drawerList,$outPath){
            ContentDirReactive::setDrawers($drawerList);
            ContentDirReactive::setDirDrawer(new DirDrawer($this->dirPath));
            $data = BladeEngine::render($this->view,[]);
            Output::saveHtml($data,$outPath);
        });
        $link = '/'.$outPath;
        LinkCache::hSet($this->dirPath,$this->view,$link);
        return $link;
    }
}