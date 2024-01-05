<?php


namespace Jggurgel\Pext\Lib;


class Fallback
{
    public function execute(Input $input)
    {
        return  Output::error(message: 'Função não encontrada');
    }
}
