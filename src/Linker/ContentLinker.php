<?php

namespace Watish\StaticPageBuilder\Linker;

use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Reactive\ContentReactive;
use Watish\StaticPageBuilder\Engine\BladeEngine;
use Watish\StaticPageBuilder\Output\Output;

class ContentLinker
{
    private string $mdPath;
    private string $view;

    public function __construct(string $mdPath, string $view)
    {
        $this->mdPath = $mdPath;
        $this->view = $view;
    }

    public function getLink() :string
    {
        if(LinkCache::hExists($this->mdPath,$this->view))
        {
            return LinkCache::hGet($this->mdPath,$this->view);
        }
        ContentReactive::setPath($this->mdPath);
        $data = BladeEngine::render($this->view,[]);
        $outPath = $this->getOutputPath();
        Output::saveHtml($data,$outPath);
        $this->deliverAsserts();
        LinkCache::hSet($this->mdPath,$this->view,$outPath);
        return $outPath;
    }

    private function getOutputPath(): string
    {
        $mdPath = $this->mdPath;
        $mdPath = preg_replace('/(\.md)$/','',$mdPath);
        if(LinkCache::hExists($this->mdPath,$this->view))
        {
            $mdPath .= '_'.$this->getViewUUID().'.html';
        }else{
            $mdPath .= '.html';
        }
        if(!str_starts_with($mdPath,'/'))
        {
            $mdPath = '/'.$mdPath;
        }

        return $mdPath;
    }

    private function getViewUUID(): string
    {
        $md5 = md5($this->view);
        return substr($md5,8,16);
    }

    private function getOutputDir(): string
    {
        return "output/".$this->getMdDir();
    }

    private function getMdDir() :string
    {
        $mdPath = $this->mdPath;
        if(!str_contains($mdPath,'/'))
        {
            return '/';
        }
        $mdPath = explode('/',$mdPath);
        $mdDir = '';
        for($i=1;$i<=(count($mdPath)-1);$i++)
        {
            $listPath = $mdPath[$i-1];
            $mdDir .= $listPath . '/';
        }
//        Logger::info($mdDir);
        return $mdDir;
    }

    private function deliverAsserts()
    {
        $mdDir = $this->getMdDir();
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $outDir = $this->getOutputDir();
        if($fileSystem->exists("{$mdDir}assets/"))
        {
            if(!$fileSystem->exists("{$outDir}assets/"))
            {
                $fileSystem->makeDirectory("{$outDir}assets");
            }
            foreach ($fileSystem->files("{$mdDir}assets/") as $filePath)
            {
                $fileName = $this->getFileName($filePath);
                if($fileSystem->exists("{$outDir}assets/{$fileName}"))
                {
                    $fileSystem->delete("{$outDir}assets/{$fileName}");
                }
                $fileSystem->copy($filePath,"{$outDir}assets/{$fileName}");
//                Logger::info("Copy From {$filePath} To output/{$outDir}assets/{$fileName} ");
            }
        }
    }

    private function getFileName(string $filePath) :string
    {
        if(!str_contains($filePath,'/'))
        {
            return $filePath;
        }
        $filePath = explode('/',$filePath);
        return $filePath[count($filePath)-1];
    }
}