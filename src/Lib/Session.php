<?php


namespace Jggurgel\Pext\Lib;


class Session
{

    public static function get(string $key, $default = '')
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }
    public static  function flash($key, $value)
    {
        $_SESSION['flash'][$key] = $value;
    }

    public static  function flashOld($value)
    {
        $_SESSION['flash']['_old'] = $value;
    }

    public static  function flashError($value)
    {
        $_SESSION['flash']['_errors'] = $value;
    }

    public static function unflash()
    {
        unset($_SESSION['flash']);
    }
}
