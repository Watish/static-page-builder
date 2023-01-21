<?php

namespace Watish\StaticPageBuilder\ArgvParser;

class ArgvParser
{
    private array $args;

    public function __construct(array $args)
    {
        unset($args[0]);
        $this->args = $args;
    }

    public function has(string $argName): bool
    {
        $args = $this->args;
        foreach ($args as $arg)
        {
            $arg = str_replace("--",'',$arg);
            if($arg == $argName)
            {
                return true;
            }
        }
        return false;
    }
}