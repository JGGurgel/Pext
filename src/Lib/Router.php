<?php


namespace Jggurgel\Pext\Lib;

class Router
{
    private const default_layout_file = 'layout.php';
    private const default_file = 'index.php';
    private const default_middleware_file = 'middleware.php';
    private const default_api_route = '/api';
    private const default_dinamic_page = '[id]';


    private $layout;
    private $isApi;
    private $middlewares;
    private $routeParameter;
    private $view;

    public function dir(){
        return $this->dir;
    }

    public function layout(){
        return $this->layout;
    }

    public function isApi(){
        return $this->isApi;
    }

    public function middlewares(){
        return $this->middlewares;
    }

    public function parameter(){
        return $this->routeParameter;
    }

    public function view(){
        return $this->view;
    }

    public function __construct(
        private $route,
        private $dir,
    ) {

        $this->execute();
    }

    private function routeParts(){

        $routeParts = array_filter(explode('/', $this->route), function ($item) {
            return $item !== '';
        });

        if (empty($routeParts)) {
            $routeParts[] = '';
        }

        return $routeParts;
    }
    private function execute()
    {
        $this->isApi = str_starts_with($this->route, self::default_api_route);
        $this->middlewares = [];
        $this->routeParameter = null;

        foreach ($this->routeParts() as $part) {

            if (is_dir(join_paths($this->dir, $part))) {
                $this->dir = join_paths($this->dir, $part);
            } else {
                $this->dir = join_paths($this->dir, self::default_dinamic_page);
                $this->routeParameter = $part;
            }
            if (is_file(join_paths($this->dir, self::default_layout_file))) {
                $this->layout = join_paths($this->dir, self::default_layout_file);
            }

            if (is_file(join_paths($this->dir, self::default_middleware_file))) {
                $this->middlewares[] = join_paths($this->dir, self::default_middleware_file);
            }
        }

        $this->view = join_paths($this->dir, self::default_file);
    }
}
