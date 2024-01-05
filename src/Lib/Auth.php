<?php

namespace Jggurgel\Pext\Lib;

class Auth
{
    private static $user = null;
    public static function setUser($user)
    {
        self::$user = $user;
    }
    public static function user()
    {
        return self::$user;
    }
    public static function throwIfNotAuthenticated()
    {
        if (self::$user == null) {
           self::throw();
        }
    }
    public static function throw(){
        throw new ReportableException("Usuário não autenticado");
    }
}