<?php

namespace Watish\StaticPageBuilder\Output;

use League\Flysystem\FileExistsException;
use Watish\StaticPageBuilder\Constructor\LocalFilesystemConstructor;
use Watish\StaticPageBuilder\Util\Logger;

class Output
{
    private static string $outPath;

    public static function setPath(string $outPath)
    {
        self::$outPath = $outPath;
    }

    public static function saveHtml(string $content,string $relativePath)
    {
        if(!str_starts_with($relativePath,'/'))
        {
            $relativePath = '/'.$relativePath;
        }
        $relativePath = '/'.self::$outPath.$relativePath;
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        if($fileSystem->exists($relativePath))
        {
            $fileSystem->delete($relativePath);
        }
        try {
            $fileSystem->write($relativePath, $content);
        } catch (FileExistsException $e) {
            Logger::exception($e);
        }
    }

    public static function deliverSource()
    {
        $fileSystem = LocalFilesystemConstructor::getIlluminateFilesystem();
        if(!$fileSystem->exists(self::$outPath."/source/"))
        {
            $fileSystem->makeDirectory(self::$outPath."/source/");
        }
        $files = $fileSystem->files("source/",true);
        foreach ($files as $filePath)
        {
            if($fileSystem->exists(self::$outPath."/{$filePath}"))
            {
                $fileSystem->delete(self::$outPath."/{$filePath}");
            }
            $fileSystem->copy($filePath,self::$outPath."/{$filePath}");
        }
    }


}