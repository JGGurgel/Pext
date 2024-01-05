<?php


namespace Jggurgel\Pext\Lib;


class Input
{
    private static $current = null;

    function __construct(
        private $data  = [],
        private $framework = []
    ) {
        self::$current = $this;
    }

    public static function current()
    {
        return self::$current;
    }
    function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    function __get($name)
    {
        return $this->data[$name]  ?? null;
    }

    function data()
    {
        return $this->data;
    }

    function route()
    {
        return $this->framework['route'];
    }

    static function fromGlobals()
    {
        $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $data = array_merge($_GET, $_POST);

        $input = new self;

        $input->data = $data;

        $input->framework = [
            'route' => $route
        ];

        return $input;
    }

    public  function validate(array $rules)
    {
        $erros = [];
        foreach ($rules as $field => $rules) {

            $rulesParts = explode('|', $rules);

            foreach ($rulesParts as $rule) {
                if ($rule === 'required' && !$this->$field) {
                    $erros[$field][] = "$field is required";
                }
                if ($rule === 'email' && !filter_var($this->$field, FILTER_VALIDATE_EMAIL)) {
                    $erros[$field][] = "$field must be a valid email";
                }
            }
        }

        if($erros){
            ValidationException::throw($erros, $this->data());
        }
    }
}
