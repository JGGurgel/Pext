<?php

use Jggurgel\Pext\Application;
use Jggurgel\Pext\Lib\Authenticate;
use Jggurgel\Pext\Lib\Database;
use Jggurgel\Pext\Lib\Input;
use Jggurgel\Pext\Lib\Output;
use Jggurgel\Pext\Lib\Session;

function app()
{
    return Application::instace();
}

function make(string $class)
{

    return app()->make($class);
}
function dd(...$args)
{
    echo "<pre>";
    var_dump(...$args);
    echo "</pre>";
    die();
}

function db(): Database
{
    return app()->make(Database::class);
}

function base_dir(...$paths)
{
    return join_paths(Application::$baseDir, ...$paths);
}

function pages_dir(...$paths)
{
    return join_paths(Application::$baseDir,  'pages', ...$paths);
}

function api_dir(...$paths)
{
    return join_paths( pages_dir(), 'api', ...$paths);
}

function join_paths(...$paths)
{
    return preg_replace('~[/\\\\]+~', DIRECTORY_SEPARATOR, implode(DIRECTORY_SEPARATOR, $paths));
}

function middleware(...$args)
{
    foreach ($args as $middleware) {
        app()->make($middleware)->execute(Input::current());
    }
}


function redirect(string $to)
{
    Output::current()->redirect($to);
}
  
function redirectBack()
{
    Output::current()->redirect($_SERVER['HTTP_REFERER']);
    return Output::current();
}



function old(string $key, $default = '')
{
    return Session::get('flash')['_old'][$key] ?? $default;
}

function error(string $key, $default = false)
{
    return Session::get('flash')['_errors'][$key] ?? $default;
}

