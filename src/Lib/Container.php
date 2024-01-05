<?php


namespace Jggurgel\Pext\Lib;


use ReflectionClass;

class Container
{

    private $binds = [];
    public function bind($interface, $implementation)
    {
        $this->binds[$interface] = $implementation;
    }
    public function make(string $class)
    {
        if ($this->isBinded($class)) {
            return $this->makeBind($class);
        }

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            return $reflection->newInstanceWithoutConstructor();
        }

        $args = [];
        foreach ($constructor->getParameters() as $parameter) {
            $type = (string)$parameter->getType();
            $args[] = $this->isBinded($type) ?
                $this->makeBind($type) :
                $this->make($type);
        }

        return $reflection->newInstanceArgs($args);
    }

    public function isBinded(string $class)
    {
        return array_key_exists($class, $this->binds);
    }

    public function makeBind(string $class)
    {
        if(is_callable($this->binds[$class])){
            return $this->binds[$class]();
        }
        if(is_string($this->binds[$class]) && class_exists($this->binds[$class])){
            return $this->make($this->binds[$class]);
        }
        
        return $this->binds[$class];
    }
}
