<?php


namespace Jggurgel\Pext\Lib;

class Password
{
    public function hash(string $input): string
    {
        return password_hash($input, PASSWORD_DEFAULT);
    }

    public function verify(string $input, string $hash)
    {
        return password_verify($input, $hash);
    }
}
