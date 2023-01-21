<?php

namespace Watish\StaticPageBuilder\Drawer;

use League\Flysystem\FileNotFoundException;
use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Linker\ContentLinker;
use Watish\StaticPageBuilder\Util\Logger;

class ContentDrawer
{
    private string $path;

    public function __construct(string $relativePath)
    {
        $this->path = $relativePath;
    }

    public function getRawContent() :string
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        try {
            return $fileSystem->read($this->path);
        } catch (FileNotFoundException $e) {
            Logger::exception($e);
            return "";
        }
    }

    public function getName() :string
    {
        $path = $this->path;
        $path = explode('/',$path);
        $path = $path[count($path)-1];
        return str_replace('.md','',$path);
    }

    public function fromDir() :string
    {
        $path = $this->path;
        if(!str_contains($path,'/'))
        {
            return "/";
        }
        $path = explode('/',$path);
        return $path[count($path)-2];
    }

    public function getPureText(int $length=0) :string
    {
        $html = $this->getHtml();
        $text = strip_tags($html);
        if($length>0)
        {
            $str_length = mb_strlen($text);
            if($str_length > $length)
            {
                return mb_substr($text,0,$length);
            }
        }
        return $text;
    }

    public function getHtml(): string
    {
        $parseDown = new \Parsedown();
        return $parseDown->parse($this->getRawContent());
    }

    public function getLastModified(): int
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        return $fileSystem->lastModified($this->path);
    }

    public function linker(string $view): ContentLinker
    {
        return new ContentLinker($this->path,$view);
    }

}