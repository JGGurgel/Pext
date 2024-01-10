<?php


namespace Jggurgel\Pext\Lib;

class Pipeline
{

    public function execute(Input $input): Output
    {
        $router = new Router(
            $input->route(),
            pages_dir()
        );

        if (!file_exists($router->view())) {
            throw new NotFoundException();
        }

        $input->id = $router->parameter();

        foreach ($router->middlewares() as $middleware) {
            require $middleware;
        }

        $output  = new Output();
        if ($isApi) {
            $output->compiledView =  json_encode(require $router->view());
            return $output;
        }

        ob_start();
        require $router->view();
        $renderedView = ob_get_clean();
        ob_start();
        require $router->layout();
        $renderedLayout = ob_get_clean();
        $compiledView = str_replace('{{body}}', $renderedView, $renderedLayout);
        $output->compiledView = $compiledView;
        return $output;
    }
}
