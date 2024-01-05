<?php


namespace Jggurgel\Pext\Lib;


use Exception;

class ValidationException extends Exception
{

    public function getErrors(){
        return $this->errors;
    }

    public function getOld(){
        return $this->old;
    }
    public function __construct(
        private $errors,
        private $old
    ) {
    }

    public static function throw($errors = [], $old = [])
    {
        throw new self($errors, $old);
    }
}
