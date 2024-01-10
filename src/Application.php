<?php

namespace Jggurgel\Pext;

use Jggurgel\Pext\Lib\AuthException;
use Jggurgel\Pext\Lib\Container;
use Jggurgel\Pext\Lib\Input;
use Jggurgel\Pext\Lib\Output;
use Jggurgel\Pext\Lib\Pipeline;
use Jggurgel\Pext\Lib\Session;
use Jggurgel\Pext\Lib\ValidationException;
use Throwable;

class Application extends Container
{
    static Application $instance;
    static string $baseDir;
    static bool $bootstraped = false;

    static function instace()
    {
        return self::$instance;
    }

    public function run(Input $input): Output
    {

        try {
            return self::execute($input);
        } catch (ValidationException $th) {
            Session::flashOld($th->getOld());
            Session::flashError($th->getErrors());
            return redirectBack();
        } catch (AuthException $authEx) {
            Session::flashError('auth', 'Usuário não autenticado');
            return Output::current()->redirect('/login');
        } catch (Throwable $ex) {
            return Output::error($ex->getMessage());
            // throw $ex;
            // return Output::view(pages_dir('error-page.php'), ['message' => $ex->getMessage()], pages_dir('layout.php'));
        }
    }

    public static function bootstrap(string $baseDir = '.')
    {
        self::$bootstraped = true;
        self::$baseDir = $baseDir;
        self::$instance =  new Application;
    }

    private static function execute(Input $request)
    {
        if (!self::$bootstraped) {
            self::bootstrap();
        }
        $pipeline = app()->make(Pipeline::class);
        return $pipeline->execute($request);
    }
}
