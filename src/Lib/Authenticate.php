<?php


namespace Jggurgel\Pext\Lib;


use App\Services\AuthService;
use App\Services\UserService;

class Authenticate
{

    function __construct(
        private JWT $jwt,
        private UserService $userService,
    ) {
    }

    public function execute(Input $input)
    {
        if (!Session::get('user')) {
            Session::flashError('Usuário não autenticado');
            throw new AuthException();
        }
    }
}
