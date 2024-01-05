<?php


namespace Jggurgel\Pext\Lib;

class Hasher
{
    public function hash(string $input): string
    {
        return md5($input);
    }
}