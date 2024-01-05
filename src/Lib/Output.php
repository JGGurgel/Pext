<?php

namespace Jggurgel\Pext\Lib;

use Throwable;

class Output
{
    private static $current = null;

    private string $compiledView = '';
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
    public static function view(string $view, $data = [], $layout = '')
    {
        $output  = new self();

        try {
            if (!file_exists($view)) {
                throw new NotFoundException();
            }
            extract($data);
            ob_start();
            require $view;
            $compiledView = ob_get_clean();
            if ($layout) {
                ob_start();
                require $layout;
                $layout = ob_get_clean();
                $compiledView = str_replace('{{body}}', $compiledView, $layout);
            }
            $output->success = true;
            $output->compiledView = $compiledView;
            return $output;
        } catch (ValidationException $th) {
            Session::flashOld($th->getOld());
            Session::flashError($th->getErrors());
            return redirectBack();
        } catch (AuthException $authEx) {
            Session::flashError('auth', 'Usuário não autenticado');
            return $output->redirect('/login');
        } catch (Throwable $ex) {
            return Output::view(web_dir('error-page.php'), ['message' => $ex->getMessage()], web_dir('layout.php'));
        }
    }
    public static function json($controller)
    {
        $output  = new self();
        try {
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
