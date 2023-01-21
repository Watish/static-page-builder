<?php

namespace Watish\StaticPageBuilder\Builder;

use Exception;
use Watish\StaticPageBuilder\Engine\BladeEngine;
use Watish\StaticPageBuilder\Linker\JobQueue;
use Watish\StaticPageBuilder\Output\Output;
use Watish\StaticPageBuilder\Scanner\PageScanner;
use Watish\StaticPageBuilder\Util\Logger;

class Builder
{
    private string $outPath;

    public function __construct(string $outPath)
    {
        $this->outPath = $outPath;
        Output::setPath($outPath);
    }

    public function build()
    {
        foreach (PageScanner::getAllViewFiles() as $viewPath)
        {
            $data = BladeEngine::render($viewPath);
            Output::saveHtml($data,$viewPath.'.html');
        }
        while (1)
        {
            if(JobQueue::isEmpty())
            {
                break;
            }
            $closure = JobQueue::pop();
            try{
                $closure();
            }catch (Exception $exception)
            {
                Logger::exception($exception);
            }
        }
        Output::deliverSource();
        Logger::info("Done");
    }

    public function rebuild()
    {
        Logger::info(exec("php ".BASE_DIR.'/cli.php'));
    }
}