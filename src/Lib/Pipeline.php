<?php


namespace Jggurgel\Pext\Lib;


class Pipeline
{
    public function execute(Input $input): Output
    {
        $routeParts = explode('/', $input->route());
        $parameter = null;
        $file = 'index.php';
        $isApi = str_starts_with($input->route(), '/api');
        $dir = '';
        $layout = web_dir('layout.php');

        foreach ($routeParts as $part) {
            if ($part === '') continue;

            if (is_dir(web_dir($dir, $part))) {
                $dir = join_paths($dir, $part);
                continue;
            }

            $dir = join_paths($dir, '[id]');
            $parameter = $part;
            $input->id = $parameter;
        }

        if (is_file(web_dir($dir, 'layout.php'))) {
            $layout = web_dir($dir, 'layout.php');
        }

        return $isApi ?
            Output::json(web_dir($dir, $file)) :
            Output::view(
                view: web_dir($dir, $file),
                data: compact('input'),
                layout: $layout
            );
    }
}
