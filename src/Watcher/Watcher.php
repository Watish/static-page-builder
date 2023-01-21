<?php

namespace Watish\StaticPageBuilder\Watcher;

use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Util\Logger;

class Watcher
{
    private \Illuminate\Filesystem\FilesystemAdapter $fileSystem;
    private array $pathList = [];
    private array $md5Hash = [];

    public function __construct(array $pathList)
    {
        $this->fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        $this->pathList = $pathList;
        $this->md5Hash = $this->scanFiles();
    }

    public function hasChanged(): bool
    {
        $newHash = $this->scanFiles();
        $oldHash = $this->md5Hash;
        $status = false;
        foreach ($newHash as $path => $md5)
        {
            if(!$this->fileSystem->exists($path))
            {
                $status = true;
                break;
            }
            if(!isset($oldHash[$path]))
            {
                $status = true;
                break;
            }
            $oldMd5 = $oldHash[$path];
            if($oldMd5 !== $md5)
            {
                $status = true;
                break;
            }
        }
        if($status)
        {
            $this->md5Hash = $newHash;
        }
        return $status;
    }

    private function scanFiles(): array
    {
        $fileSystem = $this->fileSystem;
        $md5Hash = [];
        foreach ($this->pathList as $path)
        {
            $allFiles = $fileSystem->allFiles($path);
            foreach ($allFiles as $filePath)
            {
                $fileAbsolutePath = BASE_DIR.'/'.$filePath;
                $md5 = md5_file($fileAbsolutePath);
                $md5Hash[$filePath] = $md5;
            }
        }
        return $md5Hash;
    }
}