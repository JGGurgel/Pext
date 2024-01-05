<?php

namespace Jggurgel\Pext;

use Jggurgel\Pext\Lib\Container;
use Jggurgel\Pext\Lib\Database;
use Jggurgel\Pext\Lib\Input;
use Jggurgel\Pext\Lib\Output;
use Jggurgel\Pext\Lib\Pipeline;

class Application extends Container
{
    static Application $instance;
    static string $baseDir;
    static bool $bootstraped = false;

    static function instace()
    {
        return self::$instance;
    }

    public function run(Input $input): Output
    {
        return self::execute($input);
    }

    public static function bootstrap(string $baseDir = '.')
    {
        self::$bootstraped = true;
        self::$baseDir = $baseDir;
        self::$instance =  new Application;
    }

    private static function execute(Input $request)
    {
        if (!self::$bootstraped) {
            self::bootstrap();
        }
        $pipeline = app()->make(Pipeline::class);
        return $pipeline->execute($request);
    }
}
