<?php

namespace Watish\StaticPageBuilder\Engine;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Illuminate\View\View;
use League\Flysystem\Adapter\Local;
use Watish\StaticPageBuilder\Util\Logger;

class BladeEngine
{
    private static CompilerEngine $engine;
    private static Factory $factory;
    private static bool $init = false;

    public static function  init(): void
    {
        if(self::$init)
        {
            return;
        }
        $illuminate_filesystem = new Filesystem();
        if($illuminate_filesystem->exists(BASE_DIR.'/cache/'))
        {
            $illuminate_filesystem->deleteDirectory(BASE_DIR.'/cache/');
            $illuminate_filesystem->makeDirectory(BASE_DIR.'/cache/');
        }
        self::$init = true;
    }

    private static function getFactory(): Factory
    {
        $illuminate_filesystem = new Filesystem();
        $uuid = md5(uniqid());
        if($illuminate_filesystem->exists(BASE_DIR."/cache/$uuid/"))
        {
            $illuminate_filesystem->deleteDirectory(BASE_DIR."/cache/$uuid/");
            $illuminate_filesystem->makeDirectory(BASE_DIR."/cache/$uuid/");
        }

        $engine_resolver = new EngineResolver;
        $engine_resolver->register("blade",function ($bladeCompiler){
            $filesystem = new Filesystem();
            $filesystem->files(BASE_DIR.'/template/');
            return new CompilerEngine($bladeCompiler,$filesystem);
        });

        $compiler = new BladeCompiler($illuminate_filesystem,BASE_DIR."/cache/$uuid/");
        $engine_resolver->register('blade', function () use ($compiler) {
            return new CompilerEngine($compiler);
        });

        $engine_resolver->register('php', function () use ($illuminate_filesystem) {
            return new PhpEngine($illuminate_filesystem);
        });
        $finder = new FileViewFinder(
            $illuminate_filesystem,
            [BASE_DIR.'/template']
        );
        $finder->addExtension("blade");
        $finder->addLocation(BASE_DIR.'/template/');
        $dispatcher = new Dispatcher();
        $factory = new Factory($engine_resolver,$finder,$dispatcher);

        $compiler->setPath(BASE_DIR.'/template/');
        return $factory;
    }

    public static function render(string $view ,array $data=[]): string
    {
        if(!self::$init)
        {
            self::init();
        }
        $view = self::getFactory()->make($view,$data);
        try{
            return $view->render();
        }catch (\Exception $exception)
        {
            Logger::exception($exception);
        }
        return "";
    }

    public static function view(string $view,array $data=[]): View
    {
        if(!self::$init)
        {
            self::init();
        }
        return self::getFactory()->make($view,$data);
    }
}