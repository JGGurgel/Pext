<?php

namespace Jggurgel\Pext\Lib;

use Throwable;

class Output
{
    private static $current = null;

    public string $compiledView = '';
    public bool $success = true;
    public string $message =  '';
    public array|object $data = [];
    public string $view = '';
    public string $redirect = '';
    public string $layout = '';

    public function __construct()
    {
        self::$current = $this;
    }

    public static function error($message = '', $data = []): self
    {
        return self::json(
            compact('message', 'data')
        );
    }

    public static function success($message = '', $data = []): self
    {
        $output  = new self();
        $output->message = $message;
        $output->data = $data;
        return $output;
    }
    public static function json($controller)
    {
        $output  = new self();
        try {
            if (!file_exists($controller)) {
                throw new NotFoundException();
            }
            $data = require $controller;
            $output->data = $data;
            $output->compiledView =  json_encode($data);
        } catch (\Throwable $th) {
            $output->success = false;
            $output->compiledView =  json_encode(['success' => 'false', 'message' => $th->getMessage()]);
        }
        return $output;
    }

    public static function current()
    {
        return self::$current ?? new Output();
    }

    public function redirect(string $to = '')
    {
        $this->redirect = $to;
        return $this;
    }


    public function render()
    {
        if (!$this->success) {
            http_response_code(422);
        }

        if ($this->redirect) {
            header('Location: ' . $this->redirect);
            exit;
        }

        echo $this->compiledView;

        Session::unflash();
    }
}
